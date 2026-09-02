<?php
class ModelToolImage extends Model {
	public function resize($filename, $width, $height) {
		$real = str_replace('\\', '/', realpath(DIR_IMAGE . $filename));
		$dir  = str_replace('\\', '/', DIR_IMAGE);

		// Normalize case for Windows
		if (!is_file(DIR_IMAGE . $filename) || strcasecmp(substr($real, 0, strlen($dir)), $dir) !== 0) {
		    return;
		} 
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		$image_old = $filename;

		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;
		if (!is_file(DIR_IMAGE . $image_new) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);

			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_WEBP))) { 
				return DIR_IMAGE . $image_old;
			}
			$path = '';
			$directories = explode('/', dirname($image_new));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}
			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $image_old);
				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}

		$image_new = str_replace(' ', '%20', $image_new);  // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +

		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . '/image/' . $image_new;
			// return 'https://uw-img.b-cdn.net/' . '/image/' . $image_new;
		} else {
			return $this->config->get('config_url') . 'image/' . $image_new;
		}
	}
	public function crop($filename, $width, $height) {

		$real = str_replace('\\', '/', realpath(DIR_IMAGE . $filename));
		$dir  = str_replace('\\', '/', DIR_IMAGE);
	
		// Security / file check
		if (!is_file(DIR_IMAGE . $filename) ||
			strcasecmp(substr($real, 0, strlen($dir)), $dir) !== 0) {
			return;
		}
	
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		$image_old = $filename;
	
		// Same cache location/naming as resize()
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;
	
		// Generate only when necessary
		if (!is_file(DIR_IMAGE . $image_new) ||
			filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new)) {
	
			$info = getimagesize(DIR_IMAGE . $image_old);
	
			if (!$info) {
				return;
			}
	
			$width_orig  = $info[0];
			$height_orig = $info[1];
			$image_type  = $info[2];
	
			// Load source image
			switch ($image_type) {
	
				case IMAGETYPE_JPEG:
					$source = imagecreatefromjpeg(DIR_IMAGE . $image_old);
					break;
	
				case IMAGETYPE_PNG:
					$source = imagecreatefrompng(DIR_IMAGE . $image_old);
					break;
	
				case IMAGETYPE_GIF:
					$source = imagecreatefromgif(DIR_IMAGE . $image_old);
					break;
	
				case IMAGETYPE_WEBP:
					$source = imagecreatefromwebp(DIR_IMAGE . $image_old);
					break;
	
				default:
					return DIR_IMAGE . $image_old;
			}
	
			if (!$source) {
				return DIR_IMAGE . $image_old;
			}
	
			// Target aspect ratio
			$target_ratio = $width / $height;
	
			// Original aspect ratio
			$original_ratio = $width_orig / $height_orig;
	
			/* * Determine the exact area to crop. */
			if ($original_ratio > $target_ratio) {
	
				// Original is wider.
				// Crop left + right.
	
				$crop_height = $height_orig;
				$crop_width = (int)round($height_orig * $target_ratio);
	
				$src_x = (int)round( ($width_orig - $crop_width) / 2 );
	
				$src_y = 0;
	
			} else {
	
				// Original is taller.
				// Crop top + bottom.
	
				$crop_width = $width_orig;
				$crop_height = (int)round( $width_orig / $target_ratio );
	
				$src_x = 0;
	
				$src_y = (int)round( ($height_orig - $crop_height) / 2 );
			}
	
			/* * Create the final image. */
			$destination = imagecreatetruecolor( $width, $height );
	
			// Preserve transparency for PNG/WebP
			if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_WEBP) {
	
				imagealphablending($destination, false);
				imagesavealpha($destination, true);
	
				$transparent = imagecolorallocatealpha( $destination, 255, 255, 255, 127 );
	
				imagefill( $destination, 0, 0, $transparent );
			}
	
			/* * TRUE CROP + RESIZE */
			imagecopyresampled( $destination, $source, 0, 0, $src_x, $src_y, $width, $height, $crop_width, $crop_height );
	
			/* * Create cache directories. */
			$path = '';
			$directories = explode('/', dirname($image_new));
	
			foreach ($directories as $directory) {
				$path .= '/' . $directory;
	
				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}
	
			/* * Save image. */
			switch ($image_type) {
	
				case IMAGETYPE_JPEG:
					imagejpeg( $destination, DIR_IMAGE . $image_new, 90 );
					break;
	
				case IMAGETYPE_PNG:
					imagepng( $destination, DIR_IMAGE . $image_new, 6 );
					break;
	
				case IMAGETYPE_GIF:
					imagegif( $destination, DIR_IMAGE . $image_new );
					break;
	
				case IMAGETYPE_WEBP:
					imagewebp( $destination, DIR_IMAGE . $image_new, 90 );
					break;
			}
	
			unset($source);
			unset($destination);
		}
	
		$image_new = str_replace(' ', '%20', $image_new);
	
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . '/image/' . $image_new;
		} else {
			return $this->config->get('config_url') . 'image/' . $image_new;
		}
	}
	public function webp($filename) {

		// If a full image URL was passed, convert it back to relative image path
		$filename = str_replace($this->config->get('config_ssl') . '/image/', '', $filename);
		$filename = str_replace($this->config->get('config_url') . 'image/', '', $filename);
	
		$filename = rawurldecode($filename);
	
		$source_file = DIR_IMAGE . $filename;
	
		// Check source exists
		if (!is_file($source_file)) {
			return;
		}
	
		// Check GD WebP support
		if (!function_exists('imagewebp')) {
			return;
		}
	
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
	
		// Don't convert WebP again
		if ($extension == 'webp') {
			return $this->config->get('config_ssl') . '/image/' . $filename;
		}
	
		// WebP file name
		$image_new = 
			utf8_substr(
				$filename,
				0,
				utf8_strrpos($filename, '.')
			) .
			'.webp';
	
		$destination_file = DIR_IMAGE . $image_new;
	
		// Create cache directories
		$path = '';
		$directories = explode('/', dirname($image_new));
	
		foreach ($directories as $directory) {
	
			$path .= '/' . $directory;
	
			if (!is_dir(DIR_IMAGE . $path)) {
				@mkdir(DIR_IMAGE . $path, 0777, true);
			}
		}
	
		// Convert only if required
		if (
			!is_file($destination_file) ||
			filemtime($source_file) > filemtime($destination_file)
		) {
	
			$info = getimagesize($source_file);
	
			if (!$info) {
				return;
			}
	
			switch ($info[2]) {
	
				case IMAGETYPE_JPEG:
					$source = imagecreatefromjpeg($source_file);
					break;
	
				case IMAGETYPE_PNG:
					$source = imagecreatefrompng($source_file);
					break;
	
				case IMAGETYPE_GIF:
					$source = imagecreatefromgif($source_file);
					break;
	
				case IMAGETYPE_WEBP:
					return $this->config->get('config_ssl') .
						'/image/' . $filename;
	
				default:
					return;
			}
	
			if (!$source) {
				return;
			}
	
			// Preserve PNG transparency
			if ($info[2] == IMAGETYPE_PNG) {
				imagepalettetotruecolor($source);
				imagealphablending($source, false);
				imagesavealpha($source, true);
			}
	
			// Convert to WebP
			$result = imagewebp(
				$source,
				$destination_file,
				85
			);
	
			unset($source);
	
			if (!$result || !is_file($destination_file)) {
				return;
			}
		}
	
		$image_new = str_replace(' ', '%20', $image_new);
	
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . '/image/' . $image_new;
		} else {
			return $this->config->get('config_url') . 'image/' . $image_new;
		}
	}

	public function resizeWebp($filename, $width, $height) {
		$source_file = DIR_IMAGE . $filename;
	
		// Source image doesn't exist
		if (!is_file($source_file)) {
			return;
		}
	
		// Cache filename
		$image_new = 'cache/' . utf8_substr($filename,0,utf8_strrpos($filename, '.')) .'-' . (int)$width . 'x' . (int)$height . '.webp';
	
		$destination_file = DIR_IMAGE . $image_new;
	
		// Create cache directory
		$path = '';
		$directories = explode('/', dirname($image_new));
	
		foreach ($directories as $directory) {
	
			$path .= '/' . $directory;
	
			if (!is_dir(DIR_IMAGE . $path)) {
				@mkdir(DIR_IMAGE . $path, 0777, true);
			}
		}
	
		// Generate only if required
		if (!is_file($destination_file) || filemtime($source_file) > filemtime($destination_file)) {
			$info = getimagesize($source_file);
	
			if (!$info) {
				return;
			}
	
			$width_orig  = $info[0];
			$height_orig = $info[1];
			$image_type  = $info[2];
	
			// Load source
			switch ($image_type) {
	
				case IMAGETYPE_JPEG:
					$source = imagecreatefromjpeg($source_file);
					break;
	
				case IMAGETYPE_PNG:
					$source = imagecreatefrompng($source_file);
					break;
	
				case IMAGETYPE_GIF:
					$source = imagecreatefromgif($source_file);
					break;
	
				case IMAGETYPE_WEBP:
					$source = imagecreatefromwebp($source_file);
					break;
	
				default:
					return;
			}
	
			if (!$source) {
				return;
			}
	
			// Destination
			$destination = imagecreatetruecolor($width, $height);
	
			// White background
			$white = imagecolorallocate($destination,255,255,255);
	
			imagefill($destination,0,0,$white);
	
			// Resize
			imagecopyresampled($destination,$source,0,0,0,0,$width,$height,$width_orig,$height_orig);
	
			// Save WebP
			$result = imagewebp($destination,$destination_file,85);
	
			unset($source);
			unset($destination);
	
			if (!$result || !is_file($destination_file)) {
				return;
			}
		}
	
		$image_new = str_replace(' ', '%20', $image_new);
	
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . '/image/' . $image_new;
		}
	
		return $this->config->get('config_url') . 'image/' . $image_new;
	}

	public function cropWebp($filename, $width, $height, $crop_percent = 100) {

		$source_file = DIR_IMAGE . $filename;
	
		// Source doesn't exist
		if (!is_file($source_file)) {
			return;
		}
	
		// Keep percentage within valid range
		$crop_percent = max(1, min(100, (float)$crop_percent));
	
		// Cache filename
		$image_new = 'cache/' .
			utf8_substr(
				$filename,
				0,
				utf8_strrpos($filename, '.')
			) .
			'-' . (int)$width . 'x' . (int)$height .
			'-c' . (int)$crop_percent .
			'.webp';
	
		$destination_file = DIR_IMAGE . $image_new;
	
		// Create cache directory
		$path = '';
		$directories = explode('/', dirname($image_new));
	
		foreach ($directories as $directory) {
	
			$path .= '/' . $directory;
	
			if (!is_dir(DIR_IMAGE . $path)) {
				@mkdir(DIR_IMAGE . $path, 0777, true);
			}
		}
	
		// Generate only when required
		if (
			!is_file($destination_file) ||
			filemtime($source_file) > filemtime($destination_file)
		) {
	
			$info = getimagesize($source_file);
	
			if (!$info) {
				return;
			}
	
			$width_orig  = $info[0];
			$height_orig = $info[1];
			$image_type  = $info[2];
	
			// Load source
			switch ($image_type) {
	
				case IMAGETYPE_JPEG:
					$source = imagecreatefromjpeg($source_file);
					break;
	
				case IMAGETYPE_PNG:
					$source = imagecreatefrompng($source_file);
					break;
	
				case IMAGETYPE_GIF:
					$source = imagecreatefromgif($source_file);
					break;
	
				case IMAGETYPE_WEBP:
					$source = imagecreatefromwebp($source_file);
					break;
	
				default:
					return;
			}
	
			if (!$source) {
				return;
			}
	
			/* Target aspect ratio Example: 400 / 320 = 1.25 */
			$target_ratio = $width / $height;
	
			/* First calculate the normal crop required to produce the target ratio.*/
			$crop_height = $height_orig;
			$crop_width = (int)round(
				$height_orig * $target_ratio
			);
	
			/* Apply product-specific horizontal crop. 100 = normal crop 95  = slightly more crop 90  = more crop 85  = even more crop*/
			$crop_width = (int)round(
				$crop_width * ($crop_percent / 100)
			);
	
			/** IMPORTANT: Keep crop rectangle in the same aspect ratio as the final image.*/
			$crop_height = (int)round(
				$crop_width / $target_ratio
			);
	
			/** Never crop vertically beyond image.*/
			if ($crop_height > $height_orig) {
	
				$crop_height = $height_orig;
	
				$crop_width = (int)round(
					$crop_height * $target_ratio
				);
			}
	
			/** Center crop. This removes equal amount from left and right.*/
			$src_x = (int)round(
				($width_orig - $crop_width) / 2
			);
	
			$src_y = (int)round(
				($height_orig - $crop_height) / 2
			);
	
			/** Destination*/
			$destination = imagecreatetruecolor($width,$height);
	
			// White background
			$white = imagecolorallocate($destination,255,255,255);
	
			imagefill($destination,0,0,$white);
	
			/** Crop + resize in one operation*/
			imagecopyresampled($destination,$source,0,0,$src_x,$src_y,$width,$height,$crop_width,$crop_height);
	
			/** Save directly as WebP*/
			$result = imagewebp($destination,$destination_file,85);
	
			unset($source);
			unset($destination);
	
			if (!$result || !is_file($destination_file)) {
				return;
			}
		}
	
		$image_new = str_replace(' ', '%20', $image_new);
	
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . '/image/' . $image_new;
		}
	
		return $this->config->get('config_url') . 'image/' . $image_new;
	}
}
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
		$image_new = 'cache/' .
			utf8_substr($filename, 0, utf8_strrpos($filename, '.')) .
			'-' . (int)$width . 'x' . (int)$height . '.' . $extension;
	
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
	
			/*
			 * Determine the exact area to crop.
			 */
	
			if ($original_ratio > $target_ratio) {
	
				// Original is wider.
				// Crop left + right.
	
				$crop_height = $height_orig;
				$crop_width = (int)round($height_orig * $target_ratio);
	
				$src_x = (int)round(
					($width_orig - $crop_width) / 2
				);
	
				$src_y = 0;
	
			} else {
	
				// Original is taller.
				// Crop top + bottom.
	
				$crop_width = $width_orig;
				$crop_height = (int)round(
					$width_orig / $target_ratio
				);
	
				$src_x = 0;
	
				$src_y = (int)round(
					($height_orig - $crop_height) / 2
				);
			}
	
			/*
			 * Create the final image.
			 */
			$destination = imagecreatetruecolor(
				$width,
				$height
			);
	
			// Preserve transparency for PNG/WebP
			if ($image_type == IMAGETYPE_PNG ||
				$image_type == IMAGETYPE_WEBP) {
	
				imagealphablending($destination, false);
				imagesavealpha($destination, true);
	
				$transparent = imagecolorallocatealpha(
					$destination,
					255,
					255,
					255,
					127
				);
	
				imagefill(
					$destination,
					0,
					0,
					$transparent
				);
			}
	
			/*
			 * TRUE CROP + RESIZE
			 */
			imagecopyresampled(
				$destination,
				$source,
	
				0,
				0,
	
				$src_x,
				$src_y,
	
				$width,
				$height,
	
				$crop_width,
				$crop_height
			);
	
			/*
			 * Create cache directories.
			 */
			$path = '';
			$directories = explode('/', dirname($image_new));
	
			foreach ($directories as $directory) {
	
				$path .= '/' . $directory;
	
				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}
	
			/*
			 * Save image.
			 */
			switch ($image_type) {
	
				case IMAGETYPE_JPEG:
					imagejpeg(
						$destination,
						DIR_IMAGE . $image_new,
						90
					);
					break;
	
				case IMAGETYPE_PNG:
					imagepng(
						$destination,
						DIR_IMAGE . $image_new,
						6
					);
					break;
	
				case IMAGETYPE_GIF:
					imagegif(
						$destination,
						DIR_IMAGE . $image_new
					);
					break;
	
				case IMAGETYPE_WEBP:
					imagewebp(
						$destination,
						DIR_IMAGE . $image_new,
						90
					);
					break;
			}
	
			imagedestroy($source);
			imagedestroy($destination);
		}
	
		$image_new = str_replace(' ', '%20', $image_new);
	
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . '/image/' . $image_new;
		} else {
			return $this->config->get('config_url') . 'image/' . $image_new;
		}
	}
}
<?php
namespace Mail;
class Mail {
	public function send() {
		if (is_array($this->to)) {
			$to = implode(',', $this->to);
		} else {
			$to = $this->to;
		}

		$boundary = '----=_NextPart_' . md5(time());
		$header  = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Date: ' . date('D, d M Y H:i:s O') . "\r\n";
		$header .= 'From: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . "\r\n";
		if (!$this->reply_to) {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . "\r\n";
		} else {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->reply_to) . '?= <' . $this->reply_to . '>' . "\r\n";
		}
		$header .= 'Return-Path: ' . $this->from . "\r\n";
		$header .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
		$header .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . "\r\n";
		if (!$this->html) {
			$message  = '--' . $boundary . "\r\n";
			$message .= 'Content-Type: text/plain; charset="utf-8"' . "\r\n";
			$message .= 'Content-Transfer-Encoding: 8bit' . "\r\n";
			$message .= $this->text . "\r\n";
		} else {
			$message  = '--' . $boundary . "\r\n";
			$message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . "\r\n";
			$message .= '--' . $boundary . '_alt' . "\r\n";
			$message .= 'Content-Type: text/plain; charset="utf-8"' . "\r\n";
			$message .= 'Content-Transfer-Encoding: 8bit' . "\r\n";

			if ($this->text) {
				$message .= $this->text . "\r\n";
			} else {
				$message .= 'This is a HTML email and your email client software does not support HTML email!' . "\r\n";
			}
			$message .= '--' . $boundary . '_alt' . "\r\n";
			$message .= 'Content-Type: text/html; charset="utf-8"' . "\r\n";
			$message .= 'Content-Transfer-Encoding: 8bit' . "\r\n";
			$message .= $this->html . "\r\n";
			$message .= '--' . $boundary . '_alt--' . "\r\n";
		}
		foreach ($this->attachments as $attachment) {
			if (file_exists($attachment)) {

				$handle = fopen($attachment, 'r');
				$content = fread($handle, filesize($attachment));
				fclose($handle);

				$message .= '--' . $boundary . "\r\n";
				$message .= 'Content-Type: application/octet-stream; name="' . basename($attachment) . '"' . "\r\n";
				$message .= 'Content-Transfer-Encoding: base64' . "\r\n";
				$message .= 'Content-Disposition: attachment; filename="' . basename($attachment) . '"' . "\r\n";
				$message .= 'Content-ID: <' . urlencode(basename($attachment)) . '>' . "\r\n";
				$message .= 'X-Attachment-Id: ' . urlencode(basename($attachment)) . "\r\n";
				$message .= chunk_split(base64_encode($content));
			}

		}

		$message .= '--' . $boundary . '--' . "\r\n";
		ini_set('sendmail_from', $this->from);

		if ($this->parameter) {
			mail($to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $message, $header, $this->parameter);
		} else {
			mail($to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $message, $header);
		}

	}

}
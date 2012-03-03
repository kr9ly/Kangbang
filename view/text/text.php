<?php
class TextView extends View {
	private $text = '';

	public function set($text) {
		$this->text = $text;
	}

	public function append($text) {
		$this->text = $text;
	}

	public function display() {
		echo $this->text;
	}
}
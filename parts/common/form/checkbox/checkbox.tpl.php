<label class="checkbox">
<input type="checkbox"  name="<?=$this->name ?>" id="<?=$this->name ?>" value="<?= $this->value ?>" <?= $_REQUEST[$this->_name] == $this->value ? ' checked' : ($this->checked ? ' checked' : '') ?> />
${label}
</label>
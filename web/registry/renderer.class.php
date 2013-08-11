<?php
	/*
	 * 16.03.2013
	 * Class Renderer v1.1
	 * Generuje HTML
	 * CHANGELOG:
	 * 	- v1.0: createTime
	 * 	- v1.1 [24.03.2013]: createForm(), pridane dodatocne funkcie $elemnt['other'], zmena zadavania required
	 *	- v1.1 [28.03.2013]: createForm(), pridana detekcia zaplnenia atribut tagu (zabranilo sa veciam ako class="" value="") 
	*/
class Renderer {
	private $file;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}
	
	public function createForm($data) {
		$result = "";
		$result .= '<form id="form' . $data['name'] . '" name="form' . $data['name'] . '" method="' . $data['method'] . '" action="' . $data['action'] . '" enctype="' . $data['enctype'] . '" class="' . $data['class'] . '">' . "\n";
		foreach ($data['rows'] as $row) {
			$result .= '<div class="row">' . "\n";
			foreach ($row['elements'] as $name => $element) {
				$name = lcfirst($data['name']) . '_' . $element['name'];
				if (empty($element['class'])) {
					$class = '';
				}
				else {
					$class = 'class="' . $element['class'] . '"';
				}
				if (empty($element['value'])) {
					$value = '';
				}
				else {
					$value = 'value="' . $element['value'] . '"';
				}
				if (empty($element['label'])) {
					$placeholder = '';
				}
				else {
					$placeholder = 'placeholder="' . $element['label'] . '"';
				}
				if (empty($element['id'])) {
					$id = $name;
				}
				else {
					$id = lcfirst($data['name']) . '_' . $element['id'];
				}
				if ($element['tag'] == 'input') {
					$result .= '<input type="' . $element['type'] . '" name="' . $name . '" id="' . $id . '" ' . $element['required'] . ' ' . $class . ' ' . $value . ' ' . $placeholder . ' ' . $element['custom'] . '/>' . "\n";
				}
				elseif ($element['tag'] == 'textarea') {
					$result .= '<textarea name="' . $name . '" id="' . $id . '" ' . $element['required'] . ' ' . $class . ' ' . $element['custom'] . '>' . $data['value'] . '</textarea>' . "\n";
				}
				elseif ($element['tag'] == 'select') {
					$result .= '<select name="' . $name . '" id="' . $id . '" ' . $element['required'] . ' ' . $class . ' ' . $element['custom'] . '>' . "\n";
					foreach ($element['options'] as $option) {
						if (isset($option['selected'])) {
							$result .= '<option value="' . $option['value'] . '" selected="selected">' . $data['label'] . '</option>' . "\n";
						}
						else {
							$result .= '<option value="' . $option['value'] . '">' . $data['label'] . '</option>' . "\n";
						}
					}
					$result .= '</select>' . "\n";
					$result .= '<div class="custom dropdown">' . "\n";
						foreach ($element['options'] as $option) {
							if (isset($option['selected'])) {
								$result .= '<a href="#" class="current">' . $data['label'] . '</a>' . "\n";
							}
						}
						$result .= '<a href="#" class="selector"></a>' . "\n";
						$result .= '<ul>' . "\n";
						foreach ($element['options'] as $option) {
							$result .= '<li>' . $data['label'] . '</li>' . "\n";
						}
						$result .= '</ul>' . "\n";
					$result .= '</div>' . "\n";
				}
			}
			$result .= '</div>' . "\n";
		}
		if (!empty($data['custom'])) {
			$result .= $data['custom'];
		}
		$result .= '<div class="row">' . "\n";
		$result .= '<button type="submit" class="' . $data['submit']['class'] . '" ' . $data['submit']['custom'] . '>' . $data['submit']['label'] .  '</button>' . "\n";
		$result .= '</div>' . "\n";
		$result .= '</form>' . "\n";
		return $result;
	}
	
	public function createImg($src, $class = '', $alt = '') {
		if (!empty($class)) {
			$class = 'class="' . $class . '"';
		}
		if (!empty($alt)) {
			$alt = 'alt="' . $alt . '"';
		}
		else {
			$alt = 'alt="Image"';
		}
		$src = $this->registry->getSetting('siteurl') . $src;
		return '<img ' . $class . ' ' . $alt . ' src="' . $src . '" />' . "\n";
	}
	
	public function createUserPanel() {
		$result = '';
		if ($this->registry->getObject('auth')->isLoggedIn()) {
			$result = '<section class="row">' . "\n";
			$result .= '<div class="large-12 columns">' . "\n";
			$result .= '<header><h2><small>' . $this->registry->getObject('auth')->getUser()->getFullName() . '</small></h2></header>' . "\n";
			$result .= '<ul class="side-nav">' . "\n";
			$result .= '<li><a href="' . $this->registry->getSetting('siteurl') . '/articles/new">New Article</a></li>' . "\n";
			$result .= '<li><a href="' . $this->registry->getSetting('siteurl') . '/articles/manage">Manage Articles</a></li>' . "\n";
			$result .= '<li><hr /></li>' . "\n";
			$result .= '<li><a href="' . $this->registry->getSetting('siteurl') . '/authenticate/logout">Logout</a></li>' . "\n";
			$result .= '</ul>' . "\n";
			$result .= '</div>' . "\n";
			$result .= '</section>' . "\n";
		}
		return $result;
	}
}
?>
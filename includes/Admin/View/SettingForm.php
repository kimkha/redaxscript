<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Module;

/**
 * children class to create the setting form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class SettingForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('adminSettingFormStart');
		$settingModel = new Admin\Model\Setting();
		$helperOption = new Helper\Option($this->_language);

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($this->_language->get('settings'));
		$formElement = new Admin\Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-admin-js-validate-form rs-admin-component-accordion rs-admin-form-default rs-admin-fn-clearfix'
			],
			'button' =>
			[
				'save' =>
				[
					'name' => get_class()
				]
			],
			'link' =>
			[
				'cancel' =>
				[
					'href' => $this->_registry->get('parameterRoute') . 'admin'
				]
			]
		]);

		/* create the form */

		$formElement

			/* general fieldset */

			->append('<fieldset class="rs-admin-set-accordion">')
			->append('<input id="' . get_class() . '\General" class="rs-admin-fn-status-accordion" type="radio" name="' . get_class() . '\Accordion" checked="checked" />')
			->append('<label for="' . get_class() . '\General" class="rs-admin-fn-toggle-accordion rs-admin-label-accordion">' . $this->_language->get('general') . '</label>')
			->append('<ul class="rs-admin-fn-accordion rs-admin-box-accordion"><li>')

			->label($this->_language->get('language'),
			[
				'for' => 'language'
			])
			->select($helperOption->getLanguageArray(),
			[
				$settingModel->get('language')
			],
			[
				'id' => 'language',
				'name' => 'language'
			])
			->append('</li><li>')
			->label($this->_language->get('template'),
			[
				'for' => 'template'
			])
			->select($helperOption->getTemplateArray(),
			[
				$settingModel->get('template')
			],
			[
				'id' => 'template',
				'name' => 'template'
			])
			->append('</li></ul></fieldset>')

			/* metadata fieldset */

			->append('<fieldset class="rs-admin-set-accordion">')
			->append('<input id="' . get_class() . '\Metandata" class="rs-admin-fn-status-accordion" type="radio" name="' . get_class() . '\Accordion" />')
			->append('<label for="' . get_class() . '\Metandata" class="rs-admin-fn-toggle-accordion rs-admin-label-accordion">' . $this->_language->get('metadata') . '</label>')
			->append('<ul class="rs-admin-fn-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('title'),
			[
				'for' => 'title'
			])
			->text(
			[
				'id' => 'title',
				'name' => 'title',
				'value' => $settingModel->get('title')
			])
			->append('</li><li>')
			->label($this->_language->get('author'),
			[
				'for' => 'author'
			])
			->text(
			[
				'id' => 'author',
				'name' => 'author',
				'value' => $settingModel->get('author')
			])
			->append('</li><li>')
			->label($this->_language->get('copyright'),
			[
				'for' => 'copyright'
			])
			->text(
			[
				'id' => 'copyright',
				'name' => 'copyright',
				'value' => $settingModel->get('copyright')
			])
			->append('</li><li>')
			->label($this->_language->get('description'),
			[
				'for' => 'description'
			])
			->textarea(
			[
				'class' => 'rs-admin-js-auto-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'description',
				'name' => 'description',
				'rows' => 1,
				'value' => $settingModel->get('description')
			])
			->append('</li><li>')
			->label($this->_language->get('keywords'),
			[
				'for' => 'keywords'
			])
			->textarea(
			[
				'class' => 'rs-admin-js-auto-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'keywords',
				'name' => 'keywords',
				'rows' => 1,
				'value' => $settingModel->get('keywords')
			])
			->append('</li><li>')
			->label($this->_language->get('robots'),
			[
				'for' => 'robots'
			])
			->select($helperOption->getRobotArray(),
			[
				filter_var($settingModel->get('robots'), FILTER_VALIDATE_INT)
			],
			[
				'id' => 'robots',
				'name' => 'robots'
			])
			->append('</li></ul></fieldset>')

			/* contact fieldset */

			->append('<fieldset class="rs-admin-set-accordion">')
			->append('<input id="' . get_class() . '\Contact" class="rs-admin-fn-status-accordion" type="radio" name="' . get_class() . '\Accordion" />')
			->append('<label for="' . get_class() . '\Contact" class="rs-admin-fn-toggle-accordion rs-admin-label-accordion">' . $this->_language->get('contact') . '</label>')
			->append('<ul class="rs-admin-fn-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('email'),
			[
				'for' => 'email'
			])
			->email(
			[
				'id' => 'email',
				'name' => 'email',
				'value' => $settingModel->get('email')
			])
			->append('</li><li>')
			->label($this->_language->get('subject'),
			[
				'for' => 'subject'
			])
			->text(
			[
				'id' => 'subject',
				'name' => 'subject',
				'value' => $settingModel->get('subject')
			])
			->append('</li><li>')
			->label($this->_language->get('notification'),
			[
				'for' => 'notification'
			])
			->select($helperOption->getToggleArray(),
			[
				intval($settingModel->get('notification'))
			],
			[
				'id' => 'notification',
				'name' => 'notification'
			])
			->append('</li></ul></fieldset>')

			/* formatting fieldset */

			->append('<fieldset class="rs-admin-set-accordion">')
			->append('<input id="' . get_class() . '\Formatting" class="rs-admin-fn-status-accordion" type="radio" name="' . get_class() . '\Accordion" />')
			->append('<label for="' . get_class() . '\Formatting" class="rs-admin-fn-toggle-accordion rs-admin-label-accordion">' . $this->_language->get('formatting') . '</label>')
			->append('<ul class="rs-admin-fn-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('charset'),
			[
				'for' => 'charset'
			])
			->text(
			[
				'id' => 'charset',
				'name' => 'charset',
				'value' => $settingModel->get('charset')
			])
			->append('</li><li>')
			->label($this->_language->get('divider'),
			[
				'for' => 'divider'
			])
			->text(
			[
				'id' => 'divider',
				'name' => 'divider',
				'value' => $settingModel->get('divider')
			])
			->append('</li><li>')
			->label($this->_language->get('time'),
			[
				'for' => 'time'
			])
			->select($helperOption->getTimeArray(),
			[
				$settingModel->get('time')
			],
			[
				'id' => 'time',
				'name' => 'time'
			])
			->append('</li><li>')
			->label($this->_language->get('date'),
			[
				'for' => 'date'
			])
			->select($helperOption->getDateArray(),
			[
				$settingModel->get('date')
			],
			[
				'id' => 'date',
				'name' => 'date'
			])
			->append('</li></ul></fieldset>')

			/* contents fieldset */

			->append('<fieldset class="rs-admin-set-accordion">')
			->append('<input id="' . get_class() . '\Contents" class="rs-admin-fn-status-accordion" type="radio" name="' . get_class() . '\Accordion" />')
			->append('<label for="' . get_class() . '\Contents" class="rs-admin-fn-toggle-accordion rs-admin-label-accordion">' . $this->_language->get('contents') . '</label>')
			->append('<ul class="rs-admin-fn-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('homepage'),
			[
				'for' => 'homepage'
			])
			->select($helperOption->getContentArray('articles'),
			[
				$settingModel->get('homepage')
			],
			[
				'id' => 'homepage',
				'name' => 'homepage'
			])
			->append('</li><li>')
			->label($this->_language->get('limit'),
			[
				'for' => 'limit'
			])
			->number(
			[
				'id' => 'limit',
				'name' => 'limit',
				'value' => $settingModel->get('limit')
			])
			->append('</li><li>')
			->label($this->_language->get('order'),
			[
				'for' => 'order'
			])
			->select($helperOption->getOrderArray(),
			[
				$settingModel->get('order')
			],
			[
				'id' => 'order',
				'name' => 'order'
			])
			->append('</li><li>')
			->label($this->_language->get('pagination'),
			[
				'for' => 'pagination'
			])
			->select($helperOption->getToggleArray(),
			[
				intval($settingModel->get('pagination'))
			],
			[
				'id' => 'pagination',
				'name' => 'pagination'
			])
			->append('</li></ul></fieldset>')

			/* users fieldset */

			->append('<fieldset class="rs-admin-set-accordion">')
			->append('<input id="' . get_class() . '\Users" class="rs-admin-fn-status-accordion" type="radio" name="' . get_class() . '\Accordion" />')
			->append('<label for="' . get_class() . '\Users" class="rs-admin-fn-toggle-accordion rs-admin-label-accordion">' . $this->_language->get('users') . '</label>')
			->append('<ul class="rs-admin-fn-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('registration'),
			[
				'for' => 'registration'
			])
			->select($helperOption->getToggleArray(),
			[
				intval($settingModel->get('registration'))
			],
			[
				'id' => 'registration',
				'name' => 'registration'
			])
			->append('</li><li>')
			->label($this->_language->get('verification'),
			[
				'for' => 'verification'
			])
			->select($helperOption->getToggleArray(),
			[
				intval($settingModel->get('verification'))
			],
			[
				'id' => 'verification',
				'name' => 'verification'
			])
			->append('</li><li>')
			->label($this->_language->get('recovery'),
			[
				'for' => 'recovery'
			])
			->select($helperOption->getToggleArray(),
			[
				intval($settingModel->get('recovery'))
			],
			[
				'id' => 'recovery',
				'name' => 'recovery'
			])
			->append('</li></ul></fieldset>')

			/* security fieldset */

			->append('<fieldset class="rs-admin-set-accordion">')
			->append('<input id="' . get_class() . '\Security" class="rs-admin-fn-status-accordion" type="radio" name="' . get_class() . '\Accordion" />')
			->append('<label for="' . get_class() . '\Security" class="rs-admin-fn-toggle-accordion rs-admin-label-accordion">' . $this->_language->get('security') . '</label>')
			->append('<ul class="rs-admin-fn-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('moderation'),
			[
				'for' => 'moderation'
			])
			->select($helperOption->getToggleArray(),
			[
				intval($settingModel->get('moderation'))
			],
			[
				'id' => 'moderation',
				'name' => 'moderation'
			])
			->append('</li><li>')
			->label($this->_language->get('captcha'),
			[
				'for' => 'captcha'
			])
			->select($helperOption->getCaptchaArray(),
			[
				intval($settingModel->get('captcha'))
			],
			[
				'id' => 'captcha',
				'name' => 'captcha'
			])
			->append('</li></ul></fieldset>')
			->token()
			->cancel()
			->save();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('adminSettingFormEnd');
		return $output;
	}
}

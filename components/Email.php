<?php

namespace app\components;

use Yii;

class Email extends \yii\base\Model
{
	public
		$to,
		$from,
		$cc,
		$bcc,
		$subject,
		$html,
		$text,
		$attachments,
		$model = [],
		$template,
		$method = 'mailer',
		$category;

	protected $client;
	protected $_message;

	public function rules()
	{
		return [
			[['to', 'from'], 'required'],
			[['cc', 'bcc', 'subject', 'html', 'text', 'template', 'method', 'attachments', 'model', 'category'], 'safe'],
		];
	}

	public function setTo($value)
	{
		$this->to = $value;

		return $this;
	}

	public function setFrom($value)
	{
		$this->from = $value;

		return $this;
	}

	public function setSubject($value)
	{
		$this->subject = $value;

		return $this;
	}

	public function setHtml($value)
	{
		$this->html = $value;

		return $this;
	}

	public function setText($value)
	{
		$this->text = $value;

		return $this;
	}

	public function setTemplate($value)
	{
		$this->template = $value;

		return $this;
	}

	public function setMethod($value)
	{
		$this->method = $value;

		return $this;
	}

	public function compose($layout = null, $data = [])
	{
		$this->client = Yii::$app->{$this->method}->compose();

		return $this;
	}

	public function send()
	{
		if (!$this->validate())
			return $this;

		$this->_message = $this->process();

		return $this->{'process' . ucfirst($this->method)}();
	}

	public function process()
	{
		$message = $this->client
			->setFrom($this->from)
			->setTo($this->to);

		if ($this->subject)
			$message->setSubject($this->subject);

		if ($this->html)
			$message->setHtmlBody($this->html);

		if ($this->text)
			$message->setTextBody($this->text);

		return $message;
	}

	public function processSendgrid()
	{
		if ($this->category)
			$this->_message->sendGridMessage->setCategory($this->category);

		Yii::$app->sendgrid->send($this->_message);

		return true;
	}

	public function processPostmark()
	{
		if ($this->template)
		{
			$this->_message
				->setTemplateId($this->template)
				->setTemplateModel($this->model);
		}

		$this->_message->send();

		return true;
	}
}

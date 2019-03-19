<?php

/**
 * Part of the Support package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Support
 * @version    2.0.1
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2017, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Support;

use Illuminate\Config\Repository as IlluminateConfig;
use Illuminate\Contracts\Mail\Mailer as IlluminateMailer;

class Mailer
{
    /**
     * The email subject.
     *
     * @var string
     */
    protected $subject = null;

    /**
     * The email from address.
     *
     * @var array
     */
    protected $from = [];

    /**
     * The email recipients.
     *
     * @var array
     */
    protected $recipients = [];

    /**
     * The email view.
     *
     * @var string
     */
    protected $view;

    /**
     * The email view data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * The email attachments.
     *
     * @var array
     */
    protected $attachments = [];

    /**
     * The email data attachments.
     *
     * @var array
     */
    protected $dataAttachments = [];

    /**
     * The Illuminate mailer instance.
     *
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    protected $mailer;

    /**
     * The Illuminate config repository instance.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param  \Illuminate\Contracts\Mail\Mailer  $mailer
     * @param  \Illuminate\Config\Repository  $config
     * @return void
     */
    public function __construct(IlluminateMailer $mailer, IlluminateConfig $config)
    {
        $this->mailer = $mailer;

        $this->config = $config;
    }

    /**
     * Returns the Illuminate mailer instance.
     *
     * @return \Illuminate\Contracts\Mail\Mailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * Sets the Illuminate mailer instance.
     *
     * @param  \Illuminate\Contracts\Mail\Mailer  $mailer
     * @return $this
     */
    public function setMailer(IlluminateMailer $mailer)
    {
        $this->mailer = $mailer;

        return $this;
    }

    /**
     * Returns the Illuminate config repository instance.
     *
     * @return \Illuminate\Config\Repository
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Sets the Illuminate config repository instance.
     *
     * @param  \Illuminate\Config\Repository  $config
     * @return $this
     */
    public function setConfig(IlluminateConfig $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Returns the from name.
     *
     * @return array
     */
    public function getFromName()
    {
        return array_get($this->from, 'name', null);
    }

    /**
     * Sets the from name.
     *
     * @param  string  $from
     * @return $this
     */
    public function setFromName($from)
    {
        $this->from['name'] = $from;

        return $this;
    }

    /**
     * Returns the from address.
     *
     * @return array
     */
    public function getFromAddress()
    {
        return array_get($this->from, 'address', null);
    }

    /**
     * Sets the from address.
     *
     * @param  string  $from
     * @return $this
     */
    public function setFromAddress($from)
    {
        $this->from['address'] = $from;

        return $this;
    }

    /**
     * Returns the email subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets the email subject.
     *
     * @param  string  $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Returns all the email recipients.
     *
     * @param  string  $type
     * @return array
     */
    public function getRecipients($type = null)
    {
        return array_get($this->recipients, $type, null);
    }

    /**
     * Sets multiple recipients by type.
     *
     * @param  string  $type
     * @param  array  $recipients
     * @return $this
     */
    public function setRecipients($type, array $recipients = [])
    {
        foreach ($recipients as $key => $recipient) {
            $this->setRecipient(
                $type,
                array_get($recipient, 'email'),
                array_get($recipient, 'name')
            );
        }

        return $this;
    }

    /**
     * Sets multiple "To" recipients.
     *
     * @param  array  $recipients
     * @return $this
     */
    public function setTo(array $recipients = [])
    {
        return $this->setRecipients('to', $recipients);
    }

    /**
     * Sets a single "To" recipient.
     *
     * @param  string  $email
     * @param  string  $name
     * @return $this
     */
    public function addTo($email, $name)
    {
        return $this->setRecipient('to', $email, $name);
    }

    /**
     * Sets multiple "Cc" recipients.
     *
     * @param  array  $recipients
     * @return $this
     */
    public function setCc(array $recipients = [])
    {
        return $this->setRecipients('cc', $recipients);
    }

    /**
     * Sets a single "Cc" recipient.
     *
     * @param  string  $email
     * @param  string  $name
     * @return $this
     */
    public function addCc($email, $name)
    {
        return $this->setRecipient('cc', $email, $name);
    }

    /**
     * Sets multiple "Bcc" recipients.
     *
     * @param  array  $recipients
     * @return $this
     */
    public function setBcc(array $recipients = [])
    {
        return $this->setRecipients('bcc', $recipients);
    }

    /**
     * Sets a single "Bcc" recipient.
     *
     * @param  string  $email
     * @param  string  $name
     * @return $this
     */
    public function addBcc($email, $name)
    {
        return $this->setRecipient('bcc', $email, $name);
    }

    /**
     * Sets multiple "replyTo" recipients.
     *
     * @param  array  $recipients
     * @return $this
     */
    public function setReplyTo(array $recipients = [])
    {
        return $this->setRecipients('replyTo', $recipients);
    }

    /**
     * Sets a single "replyTo" recipient.
     *
     * @param  string  $email
     * @param  string  $name
     * @return $this
     */
    public function addReplyTo($email, $name)
    {
        return $this->setRecipient('replyTo', $email, $name);
    }

    /**
     * Sets the email view and view data.
     *
     * @param  string  $view
     * @param  array  $data
     * @return $this
     */
    public function setView($view, array $data = [])
    {
        $this->view = $view;

        $this->data = $data;

        return $this;
    }

    /**
     * Returns all the attachments.
     *
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Sets multiple attachments.
     *
     * @param  array  $attachments
     * @return $this
     */
    public function setAttachments(array $attachments = [])
    {
        $this->attachments = array_merge($this->attachments, $attachments);

        return $this;
    }

    /**
     * Sets a single attachment.
     *
     * @param  string  $attachment
     * @return $this
     */
    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Returns all the data attachments.
     *
     * @return array
     */
    public function getDataAttachments()
    {
        return $this->dataAttachments;
    }

    /**
     * Sets multiple data attachments.
     *
     * @param  array  $attachments
     * @return $this
     */
    public function setDataAttachments(array $attachments = [])
    {
        $this->dataAttachments = array_merge($this->dataAttachments, $attachments);

        return $this;
    }

    /**
     * Sets a single data attachment.
     *
     * @param  string  $attachment
     * @return $this
     */
    public function addDataAttachment($attachment)
    {
        $this->dataAttachments[] = $attachment;

        return $this;
    }

    /**
     * Sends the email.
     *
     * @return int
     */
    public function send()
    {
        return $this->mailer->send($this->view, $this->data, $this->prepareCallback());
    }

    /**
     * Queue a new e-mail message for sending.
     *
     * @param  string  $queue
     * @return int
     */
    public function queue($queue = null)
    {
        return $this->mailer->queue($this->view, $this->data, $this->prepareCallback(), $queue);
    }

    /**
     * Queue a new e-mail message for sending on the given queue.
     *
     * @param  string  $queue
     * @return int
     */
    public function queueOn($queue)
    {
        return $this->mailer->queueOn($queue, $this->view, $this->data, $this->prepareCallback());
    }

    /**
     * Queue a new e-mail message for sending after (n) seconds.
     *
     * @param  int  $delay
     * @return int
     */
    public function later($delay)
    {
        return $this->mailer->later($delay, $this->view, $this->data, $this->prepareCallback());
    }

    /**
     * Prepares the email callback.
     *
     * @return \Closure
     */
    protected function prepareCallback()
    {
        return function ($mail) {
            $mail->subject($this->subject);

            $mail->from(
                array_get($this->from, 'address', $this->config->get('mail.from.address')),
                array_get($this->from, 'name', $this->config->get('mail.from.name'))
            );

            foreach ($this->recipients as $type => $recipients) {
                foreach ($recipients as $recipient) {
                    $mail->{$type}($recipient['email'], $recipient['name']);
                }
            }

            foreach ($this->attachments as $attachment) {
                $options = [];

                if (is_array($attachment)) {
                    list($attachment, $options) = $attachment;
                }

                $mail->attach($attachment, $options);
            }

            foreach ($this->dataAttachments as $name => $data) {
                $options = [];

                if (is_array($data)) {
                    list($data, $options) = $data;
                }

                $mail->attachData($data, $name, $options);
            }
        };
    }

    /**
     * Sets a single recipient by type.
     *
     * @param  string  $type
     * @param  string  $email
     * @param  string  $name
     * @return $this
     */
    protected function setRecipient($type, $email, $name)
    {
        $this->recipients[$type][$email] = compact('email', 'name');

        return $this;
    }
}

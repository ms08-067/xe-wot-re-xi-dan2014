<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 */
class ContactControllerContact extends JControllerForm
{
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}

	public function submit()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app    = JFactory::getApplication();
		$model  = $this->getModel('contact');
		$params = JComponentHelper::getParams('com_contact');
		$stub   = $this->input->getString('id');
		$id     = (int) $stub;

		// Get the data from POST
		$data  = $this->input->post->get('jform', array(), 'array');

		$contact = $model->getItem($id);

		$params->merge($contact->params);

		// Check for a valid session cookie
		if ($params->get('validate_session', 0))
		{
			if (JFactory::getSession()->getState() != 'active'){
				JError::raiseWarning(403, JText::_('COM_CONTACT_SESSION_INVALID'));

				// Save the data in the session.
				$app->setUserState('com_contact.contact.data', $data);

				// Redirect back to the contact form.
				$this->setRedirect(JRoute::_('index.php?option=com_contact&view=contact&id='.$stub, false));
				return false;
			}
		}

		// Contact plugins
		JPluginHelper::importPlugin('contact');
		$dispatcher	= JEventDispatcher::getInstance();

		// Validate the posted data.
		$form = $model->getForm();
		if (!$form)
		{
			JError::raiseError(500, $model->getError());
			return false;
		}

		$validate = $model->validate($form, $data);

		if ($validate === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_contact.contact.data', $data);

			// Redirect back to the contact form.
			$this->setRedirect(JRoute::_('index.php?option=com_contact&view=contact&id='.$stub, false));
			return false;
		}

		// Validation succeeded, continue with custom handlers
		$results	= $dispatcher->trigger('onValidateContact', array(&$contact, &$data));

		foreach ($results as $result)
		{
			if ($result instanceof Exception)
			{
				return false;
			}
		}

		// Passed Validation: Process the contact plugins to integrate with other applications
		$dispatcher->trigger('onSubmitContact', array(&$contact, &$data));

		// Send the email
		$sent = false;
		if (!$params->get('custom_reply'))
		{
			$sent = $this->_sendEmail($data, $contact);
		}

		// Set the success message if it was a success
		if (!($sent instanceof Exception))
		{
			$msg = JText::_('COM_CONTACT_EMAIL_THANKS');
		}
		else
		{
			$msg = '';
		}

		// Flush the data from the session
		$app->setUserState('com_contact.contact.data', null);

		// Redirect if it is set in the parameters, otherwise redirect back to where we came from
		if ($contact->params->get('redirect'))
		{
			$this->setRedirect($contact->params->get('redirect'), $msg);
		}
		else
		{
			$this->setRedirect(JRoute::_('index.php?option=com_contact&view=contact&id='.$stub, false), $msg);
		}

		return true;
	}
	
	public function submitinquiry(){
		// Send the email
		$app		= JFactory::getApplication();
		$mailfrom	= "info@sherwoodresidence.com";
		$fromname	= "Sherwood Residence";
		$sitename	= "Sherwood Residence | Ho Chi Minh City (Saigon), Vietnam";
		
		$subject 	= $this->input->getString('forminquiry_subject');
		$name 		= $this->input->getString('forminquiry_name');
		$email 		= $this->input->getString('forminquiry_email');
		
		$phone = $this->input->getString('forminquiry_phone');
		$company = $this->input->getString('forminquiry_company');
		$jobtitle = $this->input->getString('forminquiry_jobtitle');
		
		$datemovein = $this->input->getString('datemovein');
		$unittypes1 = $this->input->getString('unittypes1');
		$unittypes2 = $this->input->getString('unittypes2');
		$unittypes3 = $this->input->getString('unittypes3');
		$unittypes4 = $this->input->getString('unittypes4');
		$unittypes5 = $this->input->getString('unittypes5');
		$interestin1 = $this->input->getString('2room82');
		$interestin2 = $this->input->getString('2room124');
		$interestin3 = $this->input->getString('3room138');
		$interestin4 = $this->input->getString('3room143');
		$comment = $this->input->getString('textarea_comment');
		
		$unittypes = "";
		if($unittypes1!=""){$unittypes=$unittypes."- ".$unittypes1."\r\n\r\n";}
		if($unittypes2!=""){$unittypes=$unittypes."- ".$unittypes2."\r\n\r\n";}
		if($unittypes3!=""){$unittypes=$unittypes."- ".$unittypes3."\r\n\r\n";}
		if($unittypes4!=""){$unittypes=$unittypes."- ".$unittypes4."\r\n\r\n";}
		if($unittypes5!=""){$unittypes=$unittypes."- ".$unittypes5."\r\n\r\n";}
		$interestin = "";
		if($interestin1!=""){$interestin=$interestin."- ".$interestin1."\r\n\r\n";}
		if($interestin2!=""){$interestin=$interestin."- ".$interestin2."\r\n\r\n";}
		if($interestin3!=""){$interestin=$interestin."- ".$interestin3."\r\n\r\n";}
		if($interestin4!=""){$interestin=$interestin."- ".$interestin4."\r\n\r\n";}
		if($interestin5!=""){$interestin=$interestin."- ".$interestin5."\r\n\r\n";}
		//$body		= JText::_('Tel: ').$phone."<br />".$companytext.$jobtitletext.$datemovein."<br />".$unittypes."<br />".$commenttext;
		$body=			 JText::_('Subject: ').$subject."\r\n\r\n"
						.JText::_('Name: ').$name."\r\n\r\n"
						.JText::_('Email: ').$email."\r\n\r\n"
						.JText::_('Phone: ').$phone."\r\n\r\n"
						.JText::_('Company: ').$company."\r\n\r\n"
						.JText::_('Job Title: ').$jobtitle."\r\n\r\n"
						.JText::_('Estimated Move-in: ').$datemovein."\r\n\r\n"
						.JText::_('Unit Types: ').$unittypes."\r\n\r\n"
						.JText::_('Interested in: ').$interestin."\r\n\r\n"
						.JText::_('Comment: ').$comment."\r\n\r\n";
		$body	= JText::_('Dear ').$name.",\r\n\r\n"
		."Thank you for your interest in the Sherwood Residence. Please review your inquiry submission below.\r\n\r\n"
		.stripslashes($body)
		."A representative will contact you shortly about your leasing inquiry.\r\n\r\n
		If you have any questions, please email us at email: leasing@sherwoodresidence.com or call us at 
		+84 8 3823 2288.\r\n\r\n
		We look forward to welcoming you to Sherwood Residence!\r\n\r\n
		Best regards,
		Reservations Team";
		
		$recipientinquiry= array('info@sherwoodresidence.com', 'kayla.pham.it@gmail.com', $email );
		
		$mail = JFactory::getMailer();
		$mail->addRecipient($recipientinquiry);
		$mail->addReplyTo(array($email, $name));
		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject($sitename.': '.$subject);
		$mail->setBody($body);
		$sent = $mail->Send();
		// Set the success message if it was a success
		if (!($sent instanceof Exception)){
			$msg = JText::_('COM_CONTACT_EMAIL_THANKS');
		}
		else{
			$msg = '';
		}

		$this->setRedirect(JRoute::_('index.php?option=com_content&view=article&id=29&Itemid=107', false), $msg);
		return true;
	}
	
	public function submitgetintouch(){
		// Send the email
		$app		= JFactory::getApplication();
		$mailfrom	= $app->getCfg('mailfrom');
		$fromname	= $app->getCfg('fromname');
		$sitename	= $app->getCfg('sitename');
		
		$name 		= $this->input->getString('forminquiry_name');
		$email 		= $this->input->getString('forminquiry_email');
		$comment = $this->input->getString('textarea_comment');
		
		$body	= JText::_('Dear ').$name.",<br />"
		."Thank you for your interest in the Sherwood Residence. Please review your message submission below.<br />"
		.stripslashes($comment)
		."A representative will contact you shortly about your message.<br />
		If you have any questions, please email us at <a href='mailto:leasing@sherwoodresidence.com'>leasing@sherwoodresidence.com</a> or call us at 
		<a href='tel:+84838232288'>+84 8 3823 2288</a>.<br />
		We look forward to welcoming you to Sherwood Residence!<br /><br />
		Best regards,
		Reservations Team";
		
		$recipientinquiry="kayla.pham.it@gmail.com,info@sherwoodresidence.com,".$email;
		
		$mail = JFactory::getMailer();
		$mail->addRecipient($recipientinquiry);
		$mail->addReplyTo(array($email, $name));
		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject($sitename.': '.$subject);
		$mail->setBody($body);
		$sent = $mail->Send();
		// Set the success message if it was a success
		if (!($sent instanceof Exception)){
			$msg = JText::_('COM_CONTACT_EMAIL_THANKS');
		}
		else{
			$msg = '';
		}

		$this->setRedirect(JRoute::_('index.php?option=com_content&view=article&id=29&Itemid=107', false), $msg);
		return true;
	}
	private function _sendEmail($data, $contact)
	{
			$app		= JFactory::getApplication();
			if ($contact->email_to == '' && $contact->user_id != 0)
			{
				$contact_user = JUser::getInstance($contact->user_id);
				$contact->email_to = $contact_user->get('email');
			}
			$mailfrom	= $app->getCfg('mailfrom');
			$fromname	= $app->getCfg('fromname');
			$sitename	= $app->getCfg('sitename');

			$name		= $data['contact_name'];
			$email		= JStringPunycode::emailToPunycode($data['contact_email']);
			$subject	= $data['contact_subject'];
			$body		= $data['contact_message'];

			// Prepare email body
			$prefix = JText::sprintf('COM_CONTACT_ENQUIRY_TEXT', JUri::base());
			$body	= $prefix."\n".$name.' <'.$email.'>'."\r\n\r\n".stripslashes($body);

			$mail = JFactory::getMailer();
			$mail->addRecipient($contact->email_to);
			$mail->addReplyTo(array($email, $name));
			$mail->setSender(array($mailfrom, $fromname));
			$mail->setSubject($sitename.': '.$subject);
			$mail->setBody($body);
			$sent = $mail->Send();

			//If we are supposed to copy the sender, do so.

			// check whether email copy function activated
			if ( array_key_exists('contact_email_copy', $data)  )
			{
				$copytext		= JText::sprintf('COM_CONTACT_COPYTEXT_OF', $contact->name, $sitename);
				$copytext		.= "\r\n\r\n".$body;
				$copysubject	= JText::sprintf('COM_CONTACT_COPYSUBJECT_OF', $subject);

				$mail = JFactory::getMailer();
				$mail->addRecipient($email);
				$mail->addReplyTo(array($email, $name));
				$mail->setSender(array($mailfrom, $fromname));
				$mail->setSubject($copysubject);
				$mail->setBody($copytext);
				$sent = $mail->Send();
			}

			return $sent;
	}
}

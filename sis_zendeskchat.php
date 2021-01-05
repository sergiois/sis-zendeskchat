<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.SIS_ZendeskChat
 *
 * @copyright	Copyright © 2021 SergioIglesiasNET - All rights reserved.
 * @license		GNU General Public License v2.0
 * @author 		Sergio Iglesias (@sergiois)
 */

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

class PlgSystemSIS_Zendeskchat extends CMSPlugin
{
	protected $app;
	
	public function onAfterRender()
	{
		$app = Factory::getApplication();
		$menu = $app->getMenu();
		
		if ($app->isClient('administrator') || $app->get('offline', '0'))
		{
			return;
		}

		if(!in_array($menu->getActive()->id, $this->params->get('menuitemno')))
		{
			return;
		}
		
		$key_zendesk = $this->params->get('key_zendesk', '');

		if (!$key_zendesk)
		{
			return;
		}
		
		$user = Factory::getUser();
		$html = $app->getBody();

		$script_zendesk = '<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key='.$this->params->get('key_zendesk').'"> </script>';
		if(!$user->guest)
		{
			$script_zendesk .="
				<script>
				zE('webWidget', 'identify', {
					name: '".$user->name."',
					email: '".$user->email."'
				});
				</script>
			";
		}

		$html = str_replace('</head>',$script_zendesk . '</head>',$html);

		$app->setBody($html);
	}
}

<?php
/**
 * Custom Override for com_contact.form
 *
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

/* marker_class: Class based on the selection of text, none, or icons
 * jicon-text, jicon-none, jicon-icon
 */

if (($this->params->get('address_check') > 0) && 
   ($this->contact->address || $this->contact->suburb || $this->contact->state || $this->contact->country || $this->contact->postcode)) 
{ ?>

<div class="line contact-address">
	<span class="<?php echo $this->params->get('marker_class') ?>"><?php echo $this->params->get('marker_address') ?></span>
	<address>
<?php if ($this->contact->address && $this->params->get('show_street_address')) { ?>
	<span class="caddress cstreet"><?php echo nl2br($this->contact->address) ?></span>
<?php }

	if ($this->contact->postcode && $this->params->get('show_postcode')) { ?>
	<span class="caddress cpostcode"><?php echo $this->contact->postcode ?></span>
<?php }

	if ($this->contact->suburb && $this->params->get('show_suburb')) { ?>
	<span class="caddress csuburb"><?php echo $this->contact->suburb ?></span>
<?php }

	if ($this->contact->state && $this->params->get('show_state')) { ?>
	<span class="caddress cstate"><?php echo $this->contact->state ?></span>
<?php }

	if ($this->contact->country && $this->params->get('show_country')) { ?>
	<span class="caddress ccountry"><?php echo $this->contact->country ?></span>
<?php }

?>
	</address>
</div>

<?php 
} 

$show_comm = ($this->params->get('show_email') 
	|| $this->params->get('show_telephone')
	|| $this->params->get('show_fax')
	|| $this->params->get('show_mobile')
	|| $this->params->get('show_webpage') );

if ($show_comm) { ?>

<div class="line contact-contactinfo">
<?php } 

	$marker_class = $this->params->get('marker_class');

	if ($this->contact->email_to && $this->params->get('show_email')) { ?>
	<p class="comm"><span class="<?php echo $marker_class ?>"><?php echo $this->params->get('marker_email') ?></span>
		<span class="caddress cemail"><?php echo $this->contact->email_to ?></span>
	</p>
	<?php 
	}

	if ($this->contact->telephone && $this->params->get('show_telephone')) { ?>
	<p class="comm"><span class="<?php echo $marker_class ?>"><?php echo $this->params->get('marker_telephone') ?></span>
		<span class="caddress cphone"><?php echo $this->contact->telephone ?></span>
	</p>
	<?php 
	}

	if ($this->contact->fax && $this->params->get('show_fax')) { ?>
	<p class="comm"><span class="<?php echo $marker_class ?>"><?php echo $this->params->get('marker_fax') ?></span>
		<span class="caddress cfax"><?php echo $this->contact->fax ?></span>
	</p>
	<?php 
	}

	if ($this->contact->mobile && $this->params->get('show_mobile')) { ?>
	<p class="comm"><span class="<?php echo $marker_class ?>"><?php echo $this->params->get('marker_mobile') ?></span>
		<span class="caddress cmobile"><?php echo $this->contact->mobile ?></span>
	</p>
	<?php 
	}

	if ($this->contact->webpage && $this->params->get('show_webpage')) { ?>
	<p class="comm"><span class="<?php echo $marker_class ?>"></span>
		<span class="caddress cwebsite"><a href="<?php echo $this->contact->webpage ?>" target="_blank"><?php echo $this->contact->webpage ?></a></span>
	</p>
	<?php 
	}

if ($show_comm) { ?>
</div>

<?php }

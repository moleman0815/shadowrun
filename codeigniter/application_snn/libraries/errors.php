<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Errors {

    public function getError($code) {
    	switch ($code) {
    		case 'login_no_user' : return '<b>Fehlerhafte Anmeldung</b> - "Der von Ihnen eingegebene Benutzername oder das
   									Kennwort ist falsch. Versuchen Sie es erneut oder nehmen Sie Kontakt mit Ihrer
  									zust&auml;ndigen Bezirksstelle auf!"';

    				break;
    		case 'login_not_active': return '<b>Inaktive Bedienkraft</b> - "Ihre Bedienkraftnummer ist uns unbekannt oder deaktiviert.
   									Bitte informieren Sie sich bei Ihrer zust&auml;ndigen Bezirksstelle!"';
					break;
    	}

    }
}
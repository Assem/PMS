<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['form_validation_required']		= 'Le champ {field} est requis.';
$lang['form_validation_isset']			= 'The {field} field must have a value.';
$lang['form_validation_valid_email']		= 'Le champ {field} doit contenir une adresse email valide.';
$lang['form_validation_valid_emails']		= 'Le champ {field} ne peut contenir que des adresses email valides.';
$lang['form_validation_valid_url']		= 'The {field} field must contain a valid URL.';
$lang['form_validation_valid_ip']		= 'The {field} field must contain a valid IP.';
$lang['form_validation_min_length']		= 'The {field} field must be at least {param} characters in length.';
$lang['form_validation_max_length']		= 'Le champ {field} ne peut pas dépasser {param} caractères de longueur.';
$lang['form_validation_exact_length']		= 'The {field} field must be exactly {param} characters in length.';
$lang['form_validation_alpha']			= 'The {field} field may only contain alphabetical characters.';
$lang['form_validation_alpha_numeric']		= 'The {field} field may only contain alpha-numeric characters.';
$lang['form_validation_alpha_numeric_spaces']	= 'The {field} field may only contain alpha-numeric characters and spaces.';
$lang['form_validation_alpha_dash']		= 'The {field} field may only contain alpha-numeric characters, underscores, and dashes.';
$lang['form_validation_numeric']		= 'The {field} field must contain only numbers.';
$lang['form_validation_is_numeric']		= 'The {field} field must contain only numeric characters.';
$lang['form_validation_integer']		= 'The {field} field must contain an integer.';
$lang['form_validation_regex_match']		= 'The {field} field is not in the correct format.';
$lang['form_validation_matches']		= 'Le champ {field} doit correspondre au champ {param}.';
$lang['form_validation_differs']		= 'The {field} field must differ from the {param} field.';
$lang['form_validation_is_unique'] 		= 'Le champ {field} doit être unique.';
$lang['form_validation_is_unique_exclude'] 		= 'Le champ {field} doit être unique.';
$lang['form_validation_is_natural']		= 'The {field} field must only contain digits.';
$lang['form_validation_is_natural_no_zero']	= 'The {field} field must only contain digits and must be greater than zero.';
$lang['form_validation_decimal']		= 'The {field} field must contain a decimal number.';
$lang['form_validation_less_than']		= 'The {field} field must contain a number less than {param}.';
$lang['form_validation_less_than_equal_to']	= 'The {field} field must contain a number less than or equal to {param}.';
$lang['form_validation_date_less_than_equal_to']	= 'La date {field} doit être inférieur ou égale à la date {param}.';
$lang['form_validation_greater_than']		= 'The {field} field must contain a number greater than {param}.';
$lang['form_validation_greater_than_equal_to']	= 'Le champs {field} doit contenir un nombre supérieur ou égal à {param}.';
$lang['form_validation_date_greater_than_equal_to']	= 'La date {field} doit être supérieur ou égale à la date {param}.';
$lang['form_validation_error_message_not_set']	= 'Unable to access an error message corresponding to your field name {field}.';
$lang['form_validation_in_list']		= 'The {field} field must be one of: {param}.';
$lang['form_validation_valid_date']		= 'Le champ {field} doit être une date sous le format "dd/mm/yyyy".';

$lang['form_validation_external_callbacks']		= '<span class="redfield">%s</span> doit contenir:
					<ol>
						<li>Au moins ' . config_item('min_chars_for_password') . ' caractères</li>
						<li>Pas plus que ' . config_item('max_chars_for_password') . ' caractères</li>
						<li>Un nombre</li>
						<li>Un caractère miniscule</li>
						<li>Un caractère majuscule</li>
						<li>Pas d\'espaces</li>
						<li>Pas d\'anti-slashs, apostrophes ou de quotes</li>
					</ol>
				</span>';

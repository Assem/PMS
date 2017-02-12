<h3>Détail du répondant</h3>
<?php 
$this->load->helper(array('my_config'));
$data_values = array(
    'Âge' 					=> $respondent->age,
    'Email' 				=> $respondent->email,
    'GSM' 					=> $respondent->gsm,
    'Sexe' 					=> ($respondent->sexe == 'H')?'Homme':'Femme',
    'Pays' 					=> $this->respondents_model->getCountry($respondent),
    'Ville' 				=> $this->respondents_model->getCity($respondent),
    get_lov_label('marital_status') 			=> $this->respondents_model->getMaritalStatus($respondent),
    'Nombre d\'enfants' 	=> $respondent->childs_nbr,
    'Nombre de frères' 		=> $respondent->brothers_nbr,
    'Nombre de soeurs' 		=> $respondent->sisters_nbr,
    get_lov_label('educational_level') 		=> $this->respondents_model->getEducationalLevel($respondent),
    get_lov_label('professional_status') 	=> $this->respondents_model->getProfessionalStatus($respondent),
    get_lov_label('company_type') 			=> $this->respondents_model->getCompanyType($respondent),
    'Autres'                   => $respondent->notes,
);

drawModelData($data_values, 2, 'view-form');
?>
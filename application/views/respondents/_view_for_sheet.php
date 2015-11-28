<h3>Détail du répondant</h3>
<?php 
$data_values = array(
    'Âge' 					=> $respondent->age,
    'Email' 				=> $respondent->email,
    'GSM' 					=> $respondent->gsm,
    'Sexe' 					=> ($respondent->sexe == 'H')?'Homme':'Femme',
    'Pays' 					=> $this->respondents_model->getCountry($respondent),
    'Ville' 				=> $this->respondents_model->getCity($respondent),
    'Etat civil' 			=> $this->respondents_model->getMaritalStatus($respondent),
    'Nombre d\'enfants' 	=> $respondent->childs_nbr,
    'Nombre de frères' 		=> $respondent->brothers_nbr,
    'Nombre de soeurs' 		=> $respondent->sisters_nbr,
    'Niveau éducatif' 		=> $this->respondents_model->getEducationalLevel($respondent),
    'Etat professionnel' 	=> $this->respondents_model->getProfessionalStatus($respondent),
    'Entreprise' 			=> $this->respondents_model->getCompanyType($respondent)
);

drawModelData($data_values, 2, 'view-form');
?>
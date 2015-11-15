<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PMS - Questions Controller
 *
 * @author      Assem Bayahi
*/

class Questions extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('questions_model', 'main_model');
		$this->load->model('pools_model');
		$this->load->helper(array('form', 'url', 'my_date'));
        $this->load->library('form_validation');
	}
	
	/**
     * Delete a question
     *
     * @param int $id ID of the record to delete
     */
    public function delete($id=NULL) {
    	if( $this->require_role('admin,super-agent') ) {
    		$record = $this->_checkRecord($id);
    		
    		if($record) {
    			$this->main_model->delete($id);
    			$this->main_model->shiftDown($record->id_pool, $record->order);
    			
    			$this->session->set_flashdata('success', 'Question supprimée avec succès!');
    			
    			redirect('/pools/edit/'.$record->id_pool);
    		}
    	}
    }
	
	private function _getFields($action='edit', $data_values) {
    	$data = array(
			'fields' => array(
				'Description <font color="red">*</font>' 	=> form_textarea('description', $data_values['description'], array(
					'maxlength'	=> '255',
					'required' 	=> '1',
					'class'		=> 'form-control'
					)
				),
				'Type <font color="red">*</font>' 	=> form_dropdown('type', $this->main_model->getTypes(), $data_values['type'], 'class="form-control"'),
				'Obligatoire'		=> form_checkbox('required', '1', $data_values['required'], 'class="form-control"')
			)
		);
    	
    	return $data;
    }
    
	private function _setValidationRules($action='edit', $id=NULL) {
    	$unique_fct = 'is_unique_exclude';
    	$unique_fct_params = ", id, $id";
    	
    	if($action == 'add') {
    		$unique_fct = 'is_unique';
    		$unique_fct_params = "";
		}	
    	
    	$this->form_validation->set_rules('description', 'Description', 'trim|max_length[255]');
		$this->form_validation->set_rules('customer', 'Client', 'trim|max_length[120]');
    }
	
	/**
     * Add a new question to a Pool
     * 
     * @param int $pool_id ID of the Pool to witch the question will be added
     */
    public function add($pool_id) {
    	$this->output->enable_profiler(TRUE);
    	if( $this->require_role('admin,super-agent') ) {
    		if(!isset($pool_id) || !is_numeric($pool_id)) {
	    		show_404();
	    	}
	    		
	    	$pool = $this->pools_model->getRecordByID($pool_id);
	    	
	    	if(!$pool) {
	    		show_404();
	    	}
    	
    		$data = array(
    			'title' => "Ajouter une question",
    			'content' => 'questions/add'
    		);
    		
    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
    			$data_values = array(
    				'description' 			=> set_value('description'),
    				'type' 					=> set_value('type'),
    				'required' 				=> set_value('required')
    			);
    			
    			$this->_setValidationRules('add');
    			
				if ($this->form_validation->run()) {
					$data_values['required']	= (empty($data_values['required']))? '0' : '1';
					$data_values['order'] = $this->main_model->getNextIndex($pool_id);
					$data_values['id_pool'] = $pool->id;
					
					if($this->main_model->create($data_values)){
    					$this->session->set_flashdata('success', 'Question créée avec succès!');
    					
    					redirect('/pools/edit/'.$pool->id);
    				} else {
    					$this->session->set_flashdata('error', 'La création a échoué!');
    				}
                }
    		} else {
    			$data_values = array(
    				'description' 			=> '',
    				'type' 					=> '',
    				'required' 				=> FALSE
    			);
    		}
	    		
	    	$data['content_data'] = $this->_getFields('add', $data_values);
	    	$data['content_data']['pool'] = $pool;
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
	/**
     * View a pools detail
     * 
     * @param int $id ID of the pool to view
     */
    public function view($id=NULL) {
    	$this->output->enable_profiler(TRUE);
    	
    	if( $this->require_role('admin,super-agent') ) {
    		$pool = $this->_checkRecord($id);
    		
    		$data = array(
    			'title' => "Detail d'un sondage",
    			'content' => 'pools/view',
    			'pool' => $pool
    		);
    		
    		if($pool){
    			$createdBy = $this->main_model->getCreatedby($pool);
    			$createdByLink = secure_anchor("users/view/".$createdBy->user_id, 
    					strtoupper($createdBy->pms_user_last_name)." ".ucfirst($createdBy->pms_user_first_name));
    			
    			$data['content_data'] = array(
    				'fields' => array(
	    				'Code interne' 		=> $pool->code,
	    				'Client' 			=> $pool->customer,
    					'Libellé' 			=> $pool->label,
	    				'Description'		=> $pool->description,
    					'Date de début' 	=> (isset($pool->start_date))? date('d/m/Y', strtotime($pool->start_date)):'',
    					'Date de fin' 		=> (isset($pool->end_date))? date('d/m/Y', strtotime($pool->end_date)):'',
    					'Nbr maximum de fiches' 		=> $pool->max_surveys_number,
    					'Actif'				=> ($pool->actif)? 'OUI' : 'NON',
    					'Date de création' 	=> date('d/m/Y H:i:s', strtotime($pool->creation_date)),
    					'Dernière modification' => date('d/m/Y H:i:s', strtotime($pool->update_date)),
    					'Créé par' 			=> $createdByLink
    				)
	    		);
    		}
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
	/**
     * Edit a pool
     *
     * @param int $id ID of the pool to edit
     */
    public function edit($id=NULL) {
    	$this->output->enable_profiler(TRUE);
    	
    	if( $this->require_role('admin,super-agent') ) {
    		$question = $this->_checkRecord($id);
    		
    		$data = array(
    			'title' => "Edition d'une question",
    			'content' => 'questions/edit',
    			'question' => $question,
    			'pool'		=> $this->main_model->getPool($question)
    		);
    		
    		if($question) {
	    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
	    			$data_values = array(
	    				'description' 			=> set_value('description'),
    					'type' 					=> set_value('type'),
    					'required' 				=> set_value('required')
	    			);
	    			
	    			$this->_setValidationRules('edit', $id);
	    			
	    			if ($this->form_validation->run()) {
		    			$data_values['required']	= (empty($data_values['required']))? '0' : '1';
						
						if($this->main_model->update($id, $data_values)){
	    					$this->session->set_flashdata('success', 'Question mise à jour avec succès!');
	    					
	    					redirect('/pools/edit/'.$question->id_pool);
	    				} else {
	    					$this->session->set_flashdata('error', 'La mise à jour a échoué!');
	    				}
	                }
	    		} else {
	    			$data_values = array(
	    				'description' 	=> $question->description,
    					'type' 			=> $question->type,
    					'required' 		=> $question->required
	    			);
	    		}
	    		
	    		$data['content_data'] = $this->_getFields('edit', $data_values);
    		}
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
    /**
     * Change the rank/order of a question
     * 
     * @param int $id
     * @param string $action
     */
    public function move_rank($id=NULL, $action=NULL) {
    	$this->output->enable_profiler(TRUE);
    	
    	if( $this->require_role('admin,super-agent') ) {
    		$question = $this->_checkRecord($id);
    		
    		if($question) {
    			switch ($action) {
    				case 'first':
    					$new_rank = 1;
    					$function = 'shiftUp';
    					$order_from = $new_rank - 1;
    					$order_to = $question->order;
    					break;
    				case 'up':
    					$new_rank = $question->order - 1;
    					$function = 'shiftUp';
    					$order_from = $new_rank - 1;
    					$order_to = $question->order;
    					break;
    				case 'down':
    					$new_rank = $question->order + 1;
    					$function = 'shiftDown';
    					$order_from = $new_rank - 1;
    					$order_to = $question->order + 2;
    					break;
    				case 'last':
    					$new_rank = $this->main_model->getNextIndex($question->id_pool) - 1;
    					$function = 'shiftDown';
    					$order_from = $question->order;
    					$order_to = $new_rank + 1;
    					break;
    			}
    					
    			$this->main_model->$function($question->id_pool, $order_from, $order_to);
    					
    			if($this->main_model->update($id, array('order' => $new_rank))) {
    				$this->session->set_flashdata('success', 'Rang mis à jour avec succès!');
    			} else {
    				$this->session->set_flashdata('error', 'La mise à jour a échoué!');
    			}
    					
    			redirect('/pools/edit/'.$question->id_pool);
    		}
    	}
    }
}
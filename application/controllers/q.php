<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Q extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('q_model');
        $this->load->model('user_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the user list
     */
    function teamsListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('q_model');
            $data['teamRecords'] = $this->q_model->teamsListing();
            $this->global['pageTitle'] = 'CodeInsect : Teams';
            $this->loadViews("teams", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNewTeam()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $data = [];
            $this->global['pageTitle'] = 'CodeInsect : Add New Team';
            $this->loadViews("addNewTeam", $this->global, $data, NULL);
        }
    }

        
    /**
     * This function is used to add new user to the system
     */
    function addNewTeamDetails()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('team_name','Team Name','trim|required|max_length[128]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNewTeam();
            }
            else
            {
                $team_name = $this->input->post('team_name');
                
                $teamInfo = array('team_name'=>$team_name);
                
                $this->load->model('q_model');
                $result = $this->q_model->addNewTeam($teamInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Team created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Team creation failed');
                }
                
                redirect('teamsListing');
            }
        }
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteTeam($team_id = NULL)
    {

        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $team_info = array('team_id'=>$team_id);
            
            $result = $this->q_model->deleteTeam($team_id, $team_info);
            
            redirect('teamsListing');
        }
    }
    
    function editOldTeam($team_id = NULL)
    {

        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            if($team_id == null)
            {
                redirect('teamsListing');
            }
            
            $data['teamInfo'] = $this->q_model->getTeamInfo($team_id);
            
            $this->global['pageTitle'] = 'CodeInsect : Edit Team';
            
            $this->loadViews("editOldTeam", $this->global, $data, NULL);
        }
    }

    function editTeamDetails()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $team_id = $this->input->post('team_id');
            
            $this->form_validation->set_rules('team_name','Team Name','trim|required|max_length[128]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOldTeam($team_id);
            }
            else
            {
                $team_name = $this->input->post('team_name');
                
                $teamInfo = array('team_name'=>$team_name);
                
                $result = $this->q_model->editTeam($teamInfo, $team_id);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'User updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User updation failed');
                }
                
                redirect('teamsListing');
            }
        }
    }
    
}

?>
<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Q_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function teamsListing()
    {
        $this->db->select('*');
        $this->db->from('tbl_teams');
        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    }
    
       
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewTeam($teamInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_teams', $teamInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getTeamInfo($team_id)
    {
        $this->db->select('team_id, team_name');
        $this->db->from('tbl_teams');
        $this->db->where('team_id', $team_id);
        $query = $this->db->get();
        return $query->result();
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editTeam($teamInfo, $team_id)
    {
        $this->db->where('team_id', $team_id);
        $this->db->update('tbl_teams', $teamInfo);
        
        return TRUE;
    }
    
    
    
    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteTeam($team_id, $team_info)
    {
        $this->db->where('team_id', $team_id);
        $qr = $this->db->delete('tbl_teams');
        
        return $qr;
    }


    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($userId, $oldPassword)
    {
        $this->db->select('userId, password');
        $this->db->where('userId', $userId);        
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_users');
        
        $user = $query->result();

        if(!empty($user)){
            if(verifyHashedPassword($oldPassword, $user[0]->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
    
    /**
     * This function is used to change users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function changePassword($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_users', $userInfo);
        
        return $this->db->affected_rows();
    }
}

  
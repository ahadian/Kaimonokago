<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Shop_Admin_Controller {

    private $module;

	function Admin(){
		   parent::Shop_Admin_Controller();
		   // Check for access permission
			check('Pages');
            $this->module='pages';
			// Load modules/menus/models/MMenus
			$this->load->module_model('menus','MMenus');		
			// Load pages model
			$this->load->model('MPages');
			// Set breadcrumb
			$this->bep_site->set_crumb($this->lang->line('backendpro_pages'),'pages/admin');	
	}
  

	function index(){
			// we use the following variables in the view
			$data['title'] = $this->lang->line('kago_manage_page');

            // sort pages depends on languages
            // get all the languages
            // $data['languages'] =$this->MLangs->getLangDropDownWithId();
			//$data['pages'] = $this->MPages->getAllPages();
            $data['pages'] = $this->MPages->getAllPagesbyName();
            // show pages only english which has lang_id 0
            // $lang_id=0;
            //$data['pages'] = $this->MPages->getAllPagesbyLang($lang_id);

            $data['header'] = $this->lang->line('backendpro_access_control');
			// This how Bep load views
			$data['page'] = $this->config->item('backendpro_template_admin') . "admin_pages_home";
			$data['module'] = $this->module;
			$this->load->view($this->_container,$data); 
	}
  

	function create(){
		// We need TinyMCE, so load it
	  	$this->bep_assets->load_asset_group('TINYMCE');	
	   	if ($this->input->post('name')){
	   		// if info is filled in then do this
	  		$this->MPages->addPage();
	  		// This is CI way to show flashdata
	  		// $this->session->set_flashdata('message','Page created');
	  		// But here we use Bep way to display flash msg
	  		flashMsg('success',$this->lang->line('kago_page_created'));
	  		// and redirect to this index page
	  		redirect('pages/admin/index','refresh');
	  	}else{
	  		// this must be first visit to the creat page
			$data['title'] = $this->lang->line('kago_create_page');
			$data['menus'] = $this->MMenus->getAllMenusDisplay();
           
			// Set breadcrumb
			$this->bep_site->set_crumb($this->lang->line('kago_create'),'pages/admin/create');
			$data['header'] = $this->lang->line('backendpro_access_control');
			// Setting up page and telling which module 
			$data['page'] = $this->config->item('backendpro_template_admin') . "admin_pages_create";
			$data['module'] = $this->module;
			$this->load->view($this->_container,$data); 
		} 
	}
  
	  
	function edit($id=0){
			// we are using TinyMCE here, so load it.
		  	$this->bep_assets->load_asset_group('TINYMCE');
		  	if ($this->input->post('name')){
		  		// info is filled out, so the followings
		  		$this->MPages->updatePage();
		  		// This is CI way to show flashdata
		  		// $this->session->set_flashdata('message','Page updated');
		  		// But here we use Bep way to display flash msg
	  			flashMsg('success',$this->lang->line('kago_page_updated'));
		  		redirect('pages/admin/index','refresh');
		  	}else{
                $content_id = $this->uri->segment(4);
                // path
                $path = $this->uri->segment(5);
                // get all the languages 
                $data['languages'] =$this->MLangs->getLangDropDownWithId();
                // get translated languages
                $data['translanguages'] =$this->MLangs->getTransLang($this->module,$path);
		  		// set variables here
				$data['title'] = $this->lang->line('kago_edit_page');
				$data['page'] = $this->config->item('backendpro_template_admin') . "admin_pages_edit";
				$pagecontent = $this->MPages->getPage($content_id);
                $data['pagecontent'] = $pagecontent;

                if (!count($data['page'])){
					// if page is not specified redirect to index
                    flashMsg('success',$this->lang->line('kago_no_exist'));
					redirect('pages/admin/index','refresh');
				}
				//$data['menus'] = $this->MMenus->getAllMenusDisplay();
				// Set breadcrumb
               

				$this->bep_site->set_crumb($this->lang->line('kago_edit'),'pages/admin/');
				 // if lang_id is not 0 then they are translation
                /*
                $lang_id = $pagecontent['lang_id'];
                if($lang_id>0){
                    $this->bep_site->set_crumb($this->lang->line('kago_edit_translation'),'pages/admin/edit/'.$content_id);
                }
                 * 
                 */
                $data['header'] = $this->lang->line('backendpro_access_control');
				$data['module'] = $this->module;
				$this->load->view($this->_container,$data); 
			}
	}

    
	/**
     * To do:
     * Before delete is it should check it is used in menu, if it is so warn to change the menu
     * first. otherwise refuse to delete
     * @param <type> $id
     */
	function delete($id){
        // check if is used in menu
       // $this->MMenus->checkMenu();

        

			$this->MPages->deletePage($id);
			// CI way
			// $this->session->set_flashdata('message','Page deleted');
			flashMsg('success',$this->lang->line('kago_page_deleted'));
			redirect('pages/admin/index','refresh');
	}

	/*
     * moved to kaimonokago common function

	function changePageStatus($id){
		$this->MPages->changePageStatus($id);
		// CI way
		// $this->session->set_flashdata('message','Page status changed');
		flashMsg('success','Page status changed');
		redirect('pages/admin/index','refresh');
	}

*/
    
    
    function langcreate(){
        
        // we are using TinyMCE here, so load it.
        $this->bep_assets->load_asset_group('TINYMCE');
        if ($this->input->post('name')){
            // info is filled out, so the followings
            $this->MPages->addPage();
            // This is CI way to show flashdata
            // $this->session->set_flashdata('message','Page updated');
            // But here we use Bep way to display flash msg
            flashMsg('success',$this->lang->line('kago_translation_added'));
            redirect('pages/admin/index','refresh');
        }else{
           
            // id of content is segment 4
            $id = $this->uri->segment(4);
            // need to send it to a view for content id
            $data['content_id']=$id;

            // path
            $path = $this->uri->segment(5);

            // language id is segment 5
            $lang_id = $this->uri->segment(6);
            $data['lang_id']=$lang_id;
            // check if there is no translation with this lang
            // this can use a model as well
            $checktrans =$this->MKaimonokago->checktrans($this->module,$path, $lang_id);
            if (count($checktrans)){
            //redirect with warning
           // flashMsg('warning',$this->lang->line('kago_translation_exists'));
            redirect('pages/admin/index','refresh');
            }
            // do normal thing
            // get all the languages
            $data['languages'] =$this->MLangs->getLangDropDownWithId();
            $data['translanguages'] =$this->MLangs->getTransLang($this->module,$path);
            // get language info, langname. This will be used in Title
            $table ='languages';
            $selected_lang = $this->MKaimonokago->getinfo($table,$lang_id);
            $data['selected_lang']=  $selected_lang;
            // set variables here
            $data['title'] = $this->lang->line('kago_add_translation').ucwords($selected_lang['langname']);
            $data['page'] = $this->config->item('backendpro_template_admin') . "admin_lang_create";
            $data['pagecontent'] = $this->MPages->getPage($id);
            if (!count($data['page'])){
                // if page is not specified redirect to index
                flashMsg('warning',$this->lang->line('kago_no_exist'));
                redirect('pages/admin/index','refresh');
            }
            $selected_lang=ucfirst($selected_lang['langname']);// using this in bread crumb
            //$data['menus'] = $this->MMenus->getAllMenusDisplay();
            // Set breadcrumb
            $this->bep_site->set_crumb($this->lang->line('kago_edit'),'pages/admin/edit/'.$id);
            //$this->bep_site->set_crumb($this->lang->line('kago_add_translation').$selected_lang,'pages/admin/edit/'.$id."/".$lang_id);
            $data['header'] = $this->lang->line('backendpro_access_control');
            $data['module'] = $this->module;
            $this->load->view($this->_container,$data);
        }
	}


	
}//end class
?>
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Petani extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("petani_model"); //load model Petani
    }

    //method pertama yang akan di eksekusi
    public function index()
    {
        //ambil fungsi getAll untuk menampilkan semua data Petani
        $data["data_petani"] = $this->petani_model->getAll();
        
        $this->load->view('admin/head',["menu"=>20]);
        $this->load->view('admin/petani/index', $data);
        $this->load->view('admin/foot');
    }

    //method add digunakan untuk menampilkan form tambah data Petani
    public function add()
    {
        $data["data_petani"] = $this->petani_model->getAll();
        $Petani = $this->petani_model; //objek model
        $validation = $this->form_validation; //objek form validation
        $validation->set_rules($Petani->rules()); //menerapkan rules validasi pada petani_model
        //kondisi jika semua kolom telah divalidasi, maka akan menjalankan method save pada petani_model
        if ($validation->run()) {
            $this->petani_model->save();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Petani berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("petani/index");
        }
        $this->load->view('admin/head',["menu"=>20]);
        $this->load->view('admin/petani/tambah', $data);
        $this->load->view('admin/foot');
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('petani/index');

        $Petani = $this->petani_model;
        $validation = $this->form_validation;
        $validation->set_rules($Petani->rules());

        if ($validation->run()) {
            $Petani->update();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Petani berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("petani/index");
        }
        $data["data_petani"] = $Petani->getById($id);
        $this->load->view('admin/head',["menu"=>20]);
        $this->load->view('admin/petani/edit', $data);
        $this->load->view('admin/foot');
    }

    public function delete()
    {
        $id = $this->input->get('id_petani');
        if (!isset($id)) show_404();
        $this->petani_model->delete($id);
        $msg['success'] = true;
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              Data Petani berhasil <strong>dihapus!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>');
        $this->output->set_output(json_encode($msg));
    }
}


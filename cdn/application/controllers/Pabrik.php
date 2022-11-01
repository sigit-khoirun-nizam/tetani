<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pabrik extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("pabrik_model"); //load model Pabrik
    }

    //method pertama yang akan di eksekusi
    public function index()
    {
        //ambil fungsi getAll untuk menampilkan semua data Pabrik
        $data["data_pabrik"] = $this->pabrik_model->getAll();
        
        $this->load->view('admin/head',["menu"=>22]);
        $this->load->view('admin/pabrik/index', $data);
        $this->load->view('admin/foot');
    }

    //method add digunakan untuk menampilkan form tambah data Pabrik
    public function add()
    {
        $data["data_pabrik"] = $this->pabrik_model->getAll();
        $Pabrik = $this->pabrik_model; //objek model
        $validation = $this->form_validation; //objek form validation
        $validation->set_rules($Pabrik->rules()); //menerapkan rules validasi pada pabrik_model
        //kondisi jika semua kolom telah divalidasi, maka akan menjalankan method save pada pabrik_model
        if ($validation->run()) {
            $this->pabrik_model->save();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Pabrik berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("pabrik/index");
        }
        $this->load->view('admin/head',["menu"=>22]);
        $this->load->view('admin/pabrik/tambah', $data);
        $this->load->view('admin/foot');
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('pabrik/index');

        $Pabrik = $this->pabrik_model;
        $validation = $this->form_validation;
        $validation->set_rules($Pabrik->rules());

        if ($validation->run()) {
            $Pabrik->update();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Pabrik berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("pabrik/index");
        }
        $data["data_pabrik"] = $Pabrik->getById($id);
        $this->load->view('admin/head',["menu"=>22]);
        $this->load->view('admin/pabrik/edit', $data);
        $this->load->view('admin/foot');
    }

    public function delete()
    {
        $id = $this->input->get('id_pabrik');
        if (!isset($id)) show_404();
        $this->pabrik_model->delete($id);
        $msg['success'] = true;
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              Data Pabrik berhasil <strong>dihapus!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>');
        $this->output->set_output(json_encode($msg));
    }
}


<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Retail extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("retail_model"); //load model Retail
    }

    //method pertama yang akan di eksekusi
    public function index()
    {
        //ambil fungsi getAll untuk menampilkan semua data Retail
        $data["data_retail"] = $this->retail_model->getAll();
        
        $this->load->view('admin/head',["menu"=>22]);
        $this->load->view('admin/retail/index', $data);
        $this->load->view('admin/foot');
    }

    //method add digunakan untuk menampilkan form tambah data Retail
    public function add()
    {
        $data["data_retail"] = $this->retail_model->getAll();
        $Retail = $this->retail_model; //objek model
        $validation = $this->form_validation; //objek form validation
        $validation->set_rules($Retail->rules()); //menerapkan rules validasi pada retail_model
        //kondisi jika semua kolom telah divalidasi, maka akan menjalankan method save pada retail_model
        if ($validation->run()) {
            $this->retail_model->save();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Retail berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("retail/index");
        }
        $this->load->view('admin/head',["menu"=>22]);
        $this->load->view('admin/retail/tambah', $data);
        $this->load->view('admin/foot');
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('retail/index');

        $Retail = $this->retail_model;
        $validation = $this->form_validation;
        $validation->set_rules($Retail->rules());

        if ($validation->run()) {
            $Retail->update();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Retail berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("retail/index");
        }
        $data["data_retail"] = $Retail->getById($id);
        $this->load->view('admin/head',["menu"=>22]);
        $this->load->view('admin/retail/edit', $data);
        $this->load->view('admin/foot');
    }

    public function delete()
    {
        $id = $this->input->get('id_retail');
        if (!isset($id)) show_404();
        $this->retail_model->delete($id);
        $msg['success'] = true;
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              Data Retail berhasil <strong>dihapus!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>');
        $this->output->set_output(json_encode($msg));
    }
}


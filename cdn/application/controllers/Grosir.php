<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grosir extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("grosir_model"); //load model Grosir
    }

    //method pertama yang akan di eksekusi
    public function index()
    {
        //ambil fungsi getAll untuk menampilkan semua data Grosir
        $data["data_grosir"] = $this->grosir_model->getAll();
        
        $this->load->view('admin/head',["menu"=>22]);
        $this->load->view('admin/grosir/index', $data);
        $this->load->view('admin/foot');
    }

    //method add digunakan untuk menampilkan form tambah data Grosir
    public function add()
    {
        $data["data_grosir"] = $this->grosir_model->getAll();
        $Grosir = $this->grosir_model; //objek model
        $validation = $this->form_validation; //objek form validation
        $validation->set_rules($Grosir->rules()); //menerapkan rules validasi pada grosir_model
        //kondisi jika semua kolom telah divalidasi, maka akan menjalankan method save pada grosir_model
        if ($validation->run()) {
            $this->grosir_model->save();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Grosir berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("grosir/index");
        }
        $this->load->view('admin/head',["menu"=>22]);
        $this->load->view('admin/grosir/tambah', $data);
        $this->load->view('admin/foot');
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('grosir/index');

        $Grosir = $this->grosir_model;
        $validation = $this->form_validation;
        $validation->set_rules($Grosir->rules());

        if ($validation->run()) {
            $Grosir->update();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Grosir berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("grosir/index");
        }
        $data["data_grosir"] = $Grosir->getById($id);
        $this->load->view('admin/head',["menu"=>22]);
        $this->load->view('admin/grosir/edit', $data);
        $this->load->view('admin/foot');
    }

    public function delete()
    {
        $id = $this->input->get('id_grosir');
        if (!isset($id)) show_404();
        $this->grosir_model->delete($id);
        $msg['success'] = true;
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              Data Grosir berhasil <strong>dihapus!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>');
        $this->output->set_output(json_encode($msg));
    }
}


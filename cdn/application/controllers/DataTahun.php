<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataTahun extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("DataTahun_model");
    }


    public function index()
    {
        $data["title"] = "List data Tahun";
        $data["data_tahun"] = $this->DataTahun_model->getAll();
        $this->load->view('admin/head', ["menu" => 21]);
        $this->load->view('datatahun/index', $data);
        $this->load->view('admin/foot');
    }

    public function add()
    {
        $Dtahun = $this->DataTahun_model;
        $validation = $this->form_validation;
        $validation->set_rules($Dtahun->rules());

        if ($validation->run()) {
            $Dtahun->save();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Data Tahun berhasil disimpan. 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button></div>');
            redirect("datatahun");
        }

        $data["title"] = "Tambah data transporter";
        $this->load->view('admin/head', ["menu" => 21]);
        $this->load->view('datatahun/add', $data);
        $this->load->view('admin/foot');
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('datatahun');

        $Dtahun = $this->DataTahun_model;
        $validation = $this->form_validation;
        $validation->set_rules($Dtahun->rules());

        if ($validation->run()) {
            $Dtahun->update();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Data Transporter berhasil disimpan.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button></div>');
            redirect("datatahun");
        }

        $data["title"] = "Edit Data Mahasiswa";
        $data["data_tahun"] = $Dtahun->getById($id);
        if (!$data["data_tahun"]) show_404();

        $this->load->view('admin/head', ["menu" => 21]);
        $this->load->view('datatahun/edit', $data);
        $this->load->view('admin/foot');
    }

    public function delete()
    {
        $id = $this->input->get('id');
        if (!isset($id)) show_404();

        $this->DataTransporter_model->delete($id);

        $msg['success'] = true;
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        Data Transporter berhasil dihapus.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button></div>');
        $this->output->set_output(json_encode($msg));
    }
}

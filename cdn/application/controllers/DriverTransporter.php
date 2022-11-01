<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DriverTransporter extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("DriverTransporter_model");
    }


    public function index()
    {
        $data["title"] = "List data transporter";
        $data["data_transporter"] = $this->DriverTransporter_model->getAll();
        $this->load->view('admin/head', ["menu" => 21]);
        $this->load->view('drivertransporter/index', $data);
        $this->load->view('admin/foot');
    }

    public function add()
    {
        $Dtransporter = $this->DriverTransporter_model;
        $validation = $this->form_validation;
        $validation->set_rules($Dtransporter->rules());

        if ($validation->run()) {
            $Dtransporter->save();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Data Transporter berhasil disimpan. 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button></div>');
            redirect("drivertransporter");
        }

        $data["title"] = "Tambah data driver";
        $this->load->view('admin/head', ["menu" => 21]);
        $this->load->view('drivertransporter/add', $data);
        $this->load->view('admin/foot');
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('drivertransporter');

        $Dtransporter = $this->DriverTransporter_model;
        $validation = $this->form_validation;
        $validation->set_rules($Dtransporter->rules());

        if ($validation->run()) {
            $Dtransporter->update();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Data Transporter berhasil disimpan.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button></div>');
            redirect("drivertransporter");
        }

        $data["title"] = "Edit Data Mahasiswa";
        $data["driver_transporter"] = $Dtransporter->getById($id);
        if (!$data["driver_transporter"]) show_404();

        $this->load->view('admin/head', ["menu" => 21]);
        $this->load->view('drivertransporter/edit', $data);
        $this->load->view('admin/foot');
    }

    public function delete()
    {
        $id = $this->input->get('id');
        if (!isset($id)) show_404();

        $this->DriverTransporter_model->delete($id);

        $msg['success'] = true;
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        Data Transporter berhasil dihapus.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button></div>');
        $this->output->set_output(json_encode($msg));
    }
}
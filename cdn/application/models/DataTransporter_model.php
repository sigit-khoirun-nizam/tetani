<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataTransporter_model extends CI_Model
{
    private $table = 'blw_d_transporter';

    public function rules()
    {
        return [
            [
                'field' => 'kode_transporter',
                'label' => 'kode_transporter'
            ],
            [
                'field' => 'nama',
                'label' => 'nama',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'no_hp',
                'label' => 'no hp',
                'rules' => 'trim|required|numeric|min_length[9]|max_length[15]'
            ],
            [
                'field' => 'email',
                'label' => 'email',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'alamat',
                'label' => 'alamat',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'provinsi',
                'label' => 'provinsi',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'kota',
                'label' => 'kota',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'status',
                'label' => 'status',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'account_bank',
                'label' => 'account bank',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'rekening_bank',
                'label' => 'rekening bank',
                'rules' => 'trim|required'
            ],
        ];
    }

    public function getById($id)
    {
        return $this->db->get_where($this->table, ["id" => $id])->row();
    }

    public function getAll()
    {
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function save()
    {
        $data = array(
            "kode_transporter" => $this->input->post('kode_transporter'),
            "nama" => $this->input->post('nama'),
            "no_hp" => $this->input->post('no_hp'),
            "email" => $this->input->post('email'),
            "alamat" => $this->input->post('alamat'),
            "provinsi" => $this->input->post('provinsi'),
            "kota" => $this->input->post('kota'),
            "status" => $this->input->post('status'),
            "account_bank" => $this->input->post('account_bank'),
            "rekening_bank" => $this->input->post('rekening_bank'),
        );

        return $this->db->insert($this->table, $data);
    }

    public function update()
    {
        $data = array(
            "kode_transporter" => $this->input->post('kode_transporter'),
            "nama" => $this->input->post('nama'),
            "no_hp" => $this->input->post('no_hp'),
            "email" => $this->input->post('email'),
            "alamat" => $this->input->post('alamat'),
            "provinsi" => $this->input->post('provinsi'),
            "kota" => $this->input->post('kota'),
            "status" => $this->input->post('status'),
            "account_bank" => $this->input->post('account_bank'),
            "rekening_bank" => $this->input->post('rekening_bank'),
        );

        return $this->db->update($this->table, $data, array('id' => $this->input->post('id')));
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, array('id' => $id));
    }
}
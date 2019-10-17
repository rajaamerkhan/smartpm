<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FinancialOptions extends CI_Controller
{
    private $title = 'Financial Options';

    public function __construct()
    {
        parent::__construct();

        $this->load->model(['FinancialTypesModel', 'FinancialSubtypesModel', 'FinancialAccCodesModel', 'FinancialMethodsModel', 'FinancialBankAccsModel']);

        $this->type = new FinancialTypesModel();
        $this->subtype = new FinancialSubtypesModel();
        $this->accCode = new FinancialAccCodesModel();
        $this->method = new FinancialMethodsModel();
        $this->bankAcc = new FinancialBankAccsModel();
    }

    public function index()
    {
        authAccess();

        $types = $this->type->allTypes();
        $subtypes = $this->subtype->allSubtypes();
        $accCodes = $this->accCode->allAccCodes();
        $methods = $this->method->allMethods();
        $bankAccs = $this->bankAcc->allBankAccs();

        $this->load->view('header', [
            'title' => $this->title
        ]);
        $this->load->view('setting/financial-options', [
            'types' => $types,
            'subtypes' => $subtypes,
            'accCodes' => $accCodes,
            'methods' => $methods,
            'bankAccs' => $bankAccs
        ]);
        $this->load->view('footer');
    }

    public function insertType()
    {
        authAccess();

        $this->form_validation->set_rules('name', 'Name', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $data = $this->input->post();
            $insert = $this->type->insert([
                'name' => $data['name']
            ]);
            if (!$insert) {
                $this->session->set_flashdata('errors', '<p>Unable to Create Financial Option Type.</p>');
            }
        } else {
            $this->session->set_flashdata('errors', validation_errors());
        }
        redirect('setting/financial-options');
    }

    public function deleteType($id)
    {
        authAccess();

        $this->type->delete($id);
        redirect('setting/financial-options');
    }

    public function insertSubtype()
    {
        authAccess();

        $this->form_validation->set_rules('name', 'Name', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $data = $this->input->post();
            $insert = $this->subtype->insert([
                'name' => $data['name']
            ]);
            if (!$insert) {
                $this->session->set_flashdata('errors', '<p>Unable to Create Financial Option Sub Type.</p>');
            }
        } else {
            $this->session->set_flashdata('errors', validation_errors());
        }
        redirect('setting/financial-options');
    }

    public function deleteSubtype($id)
    {
        authAccess();

        $this->subtype->delete($id);
        redirect('setting/financial-options');
    }

    public function insertAccCode()
    {
        authAccess();

        $this->form_validation->set_rules('name', 'Name', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $data = $this->input->post();
            $insert = $this->accCode->insert([
                'name' => $data['name']
            ]);
            if (!$insert) {
                $this->session->set_flashdata('errors', '<p>Unable to Create Financial Option Type.</p>');
            }
        } else {
            $this->session->set_flashdata('errors', validation_errors());
        }
        redirect('setting/financial-options');
    }

    public function deleteAccCode($id)
    {
        authAccess();

        $this->accCode->delete($id);
        redirect('setting/financial-options');
    }

    public function insertMethod()
    {
        authAccess();

        $this->form_validation->set_rules('name', 'Name', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $data = $this->input->post();
            $insert = $this->method->insert([
                'name' => $data['name']
            ]);
            if (!$insert) {
                $this->session->set_flashdata('errors', '<p>Unable to Create Financial Option Type.</p>');
            }
        } else {
            $this->session->set_flashdata('errors', validation_errors());
        }
        redirect('setting/financial-options');
    }

    public function deleteMethod($id)
    {
        authAccess();

        $this->method->delete($id);
        redirect('setting/financial-options');
    }

    public function insertBankAcc()
    {
        authAccess();

        $this->form_validation->set_rules('name', 'Name', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $data = $this->input->post();
            $insert = $this->bankAcc->insert([
                'name' => $data['name']
            ]);
            if (!$insert) {
                $this->session->set_flashdata('errors', '<p>Unable to Create Financial Option Type.</p>');
            }
        } else {
            $this->session->set_flashdata('errors', validation_errors());
        }
        redirect('setting/financial-options');
    }

    public function deleteBankAcc($id)
    {
        authAccess();

        $this->bankAcc->delete($id);
        redirect('setting/financial-options');
    }
}
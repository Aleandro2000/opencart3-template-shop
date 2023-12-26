<?php
class ControllerExtensionModuleImportProducts extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/import_products');
        $this->document->setTitle($this->language->get('heading_title'));

        // Add any necessary validation for user permissions

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (isset($this->request->files['file']) && is_uploaded_file($this->request->files['file']['tmp_name'])) {
                $file_info = pathinfo($this->request->files['file']['name']);

                if (strtolower($file_info['extension']) === 'json') {
                    $json_data = file_get_contents($this->request->files['file']['tmp_name']);
                    $data = json_decode($json_data, true);

                    if ($data) {
                        $this->load->model('extension/module/import_products');
                        $this->model_extension_module_import_products->importProducts($data);

                        $this->session->data['success'] = $this->language->get('text_success');
                        $this->response->redirect($this->url->link('extension/module/import_products', 'user_token=' . $this->session->data['user_token'], true));
                    } else {
                        $this->error['warning'] = $this->language->get('error_invalid_json');
                    }
                } else {
                    $this->error['warning'] = $this->language->get('error_not_json_file');
                }
            } else {
                $this->error['warning'] = $this->language->get('error_upload');
            }
        }

        $this->getForm();
    }

    protected function validate() {
        // Add any validation logic here
        return true;
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_upload'] = $this->language->get('text_upload');
        $data['text_allowed_extensions'] = $this->language->get('text_allowed_extensions');
        $data['text_instruction'] = $this->language->get('text_instruction');
        $data['entry_import_file'] = $this->language->get('entry_import_file');
        $data['button_import'] = $this->language->get('button_import');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

        $data['action'] = $this->url->link('extension/module/import_products', 'user_token=' . $this->session->data['user_token'], true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/import_products', $data));
    }
}
?>

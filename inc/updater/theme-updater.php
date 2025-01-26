<?php
if (!class_exists('Theme_Updater')) {
    class Theme_Updater {
        private $slug; // Temos slug'as
        private $theme_data; // Temos duomenys
        private $username; // GitHub vartotojo vardas
        private $repo; // GitHub repozitorijos pavadinimas
        private $github_response; // GitHub atsakas

        public function __construct() {
            $this->slug = get_template(); // Gauname temos slug'ą
            add_filter('pre_set_site_transient_update_themes', array($this, 'check_for_update')); // Tikriname atnaujinimus
            add_filter('themes_api', array($this, 'theme_api_call'), 10, 3); // Temos API kvietimas
        }

        public function check_for_update($transient) {
            if (empty($transient->checked)) {
                return $transient;
            }

            $this->username = 'tavoweb'; // Pakeiskite į savo GitHub vartotojo vardą
            $this->repo = 'Neptune-by-Osetin'; // Pakeiskite į savo repozitorijos pavadinimą

            $this->theme_data = wp_get_theme($this->slug); // Gauname temos duomenis
            $current_version = $this->theme_data->get('Version'); // Gauname dabartinę temos versiją

            $this->get_repository_info(); // Gauname informaciją iš GitHub

            if (version_compare($current_version, $this->github_response['tag_name'], '<')) {
                $transient->response[$this->slug] = array(
                    'theme' => $this->slug,
                    'new_version' => $this->github_response['tag_name'],
                    'url' => $this->github_response['html_url'],
                    'package' => $this->github_response['zipball_url'],
                );
            }

            return $transient;
        }

        public function theme_api_call($def, $action, $args) {
            if ($args->slug != $this->slug) {
                return $def;
            }

            $this->username = 'tavoweb'; // Pakeiskite į savo GitHub vartotojo vardą
            $this->repo = 'Neptune-by-Osetin'; // Pakeiskite į savo repozitorijos pavadinimą

            $this->get_repository_info(); // Gauname informaciją iš GitHub

            $args->version = $this->github_response['tag_name'];
            $args->last_updated = $this->github_response['published_at'];
            $args->sections = array(
                'description' => $this->github_response['body'],
            );

            return $args;
        }

        private function get_repository_info() {
            if (is_null($this->github_response)) {
                $request_uri = sprintf('https://api.github.com/repos/%s/%s/releases/latest', $this->username, $this->repo);
                $response = wp_remote_get($request_uri);

                if (is_wp_error($response)) {
                    return false;
                }

                $this->github_response = json_decode($response['body'], true);
            }
        }
    }

    new Theme_Updater();
}
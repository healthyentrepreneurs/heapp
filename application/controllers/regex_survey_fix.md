case 3:
                    $id = $this->input->get('id');
                    $attempt_n_n_one = $this->universal_model->selectzy('*', 'survey', 'slug', 1, 'id', $id);
                    // Method 1
                    // $pattern = '/\[removed\]([^\s,]+)/';
                    // foreach ($attempt_n_n_one as $key => $value) {
                    //     $attempt_n_n_one[$key] = preg_replace_callback($pattern, function ($matches) {
                    //         $imageType = getImageTypeFromBase64($matches[1]);
                    //         if ($imageType) {
                    //             return '"' . $imageType . $matches[1];
                    //         } else {
                    //             return $matches[1];
                    //         }
                    //     }, $value);
                    // }
                    // Method 2
                    $pattern = '/\[(removed)\]([^\s,]+)/';
                    foreach ($attempt_n_n_one as $key => $value) {
                        $attempt_n_n_one[$key] = preg_replace_callback($pattern, function ($matches) {
                            if ($matches[1] === 'removed') {
                                $imageType = getImageTypeFromBase64($matches[2]);
                                return '"' . $imageType . $matches[2];
                                // return '"' . $matches[2] . '"';
                            } else {
                                return $matches[0];
                            }
                        }, $value);
                    }
                    $data['content_admin'] = 'pages/admin/surveyinstance';
                    $data['surveydataone'] = array_shift($attempt_n_n_one);
                    $data['id'] = $id;
                    $this->load->view('pages/hometwo', $data);
                    break;

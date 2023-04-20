<?php 

// Neste código são criadas as inscrições dos estudantes de acordo com o seu curso, o objetivo é que ao criar o curso sejam importadas automaticamente 
// as informações dos alunos, de modo que, o aluno recebe um e-mail com o convite para acessar a plataforma do curso caso já possua cadastro, se não
// ele recebe um e-mail convocando a sua autoinscrição. 

require_once(dirname(__FILE__) . '/../../../config.php');
global $USER, $PAGE, $OUTPUT;

$PAGE->set_pagelayout('admin');
$PAGE->set_url("/block/extensao/cadastar_alunos");
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('pluginname', 'block_extensao'));
require_login();

// requerimentos
require_once('../utils/forms.php');
require_once('../src/turmas.php');
require_once('../src/Service/Query.php');
require_once('../../../config.php');
require_once("$CFG->dirroot/user/lib.php");

use block_extensao\Service\Query;


echo "$CFG->dirroot/user/lib.php";

echo "Socorro!";



// funcao que obtem os alunos na base de dados do moodle
function obter_alunos(){
    global $DB;
    $query_alunos = "SELECT nome, codpes, email, codofeatvceu FROM {extensao_aluno} WHERE id_moodle IS NULL";
    $data = $DB->get_records('extensao_aluno', array('id_moodle'=>NULL));
    return $data;
 }


function obtem_usuario($field, $value) {
    global $DB;

    $params = array($field => $value);
    $users = $DB->get_records('user', $params);
    return $users;
}

// O E-MAIL NAO ESTA VINDO DO REPLICADO

// Funcao para cadastrar um aluno no Moodle
function cadastra_usuario($alunos) {
    foreach($alunos as $estudante) {
        $newuser = new stdClass();
        $newuser->username = $estudante->codpes;
        $newuser->firstname = $estudante->nome;
        //$newuser->email = $estudante->email;
        $newuser->id = $estudante->codpes;
        $base = array('field' => 'username', 'value' => $estudante->codpes);
        $existeuser = obtem_usuario($base['field'], $base['value']);
        if (!empty($existeuser)) {
            // Usuario ja existe, imprimir mensagem de que ja esta cadastrado no Moodle.
            echo ("O usuário " . $estudante->nome  ." já está cadastrado no sistema. <br>");
        } else {
            // Usuario nao existe, cadastrar no Moodle.
            $user_created = user_create_user($newuser);
            if ($user_created === false) {
                // Tratamento de erro caso a funcao user_create_user() retorne false
                echo ("Erro ao cadastrar o usuário " . $estudante->nome . ". <br>");
            } else {
                \core\notification::success('Estudante ' . $estudante->nome . ' inscrito com sucesso!');
            }
        }
    }
}
$alunos = obter_alunos();

echo "<pre>/";
var_dump($alunos);
/*
echo cadastra_usuario ($alunos);
/*
// Funcao para matricular os alunos no curso conforme o codigo de oferecimento

function matricula_usuario($alunos) {
    foreach($alunos as $estudante) {
        $curso = $estudante->codofeatvceu;
        $matricula = $estudante->codpes;
        $base = array('field' => 'username', 'value' => $estudante->codpes);
        $existematricula = 
        enrol_self_enrol_user($curso, $matricula)
}




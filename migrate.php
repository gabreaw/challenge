<?php
/*
  Descrição do Desafio:
    Você precisa realizar uma migração dos dados fictícios que estão na pasta <dados_sistema_legado> para a base da clínica fictícia MedicalChallenge.
    Para isso, você precisa:
      1. Instalar o MariaDB na sua máquina. Dica: Você pode utilizar Docker para isso;
      2. Restaurar o banco da clínica fictícia Medical Challenge: arquivo <medical_challenge_schema>;
      3. Migrar os dados do sistema legado fictício que estão na pasta <dados_sistema_legado>:
        a) Dica: você pode criar uma função para importar os arquivos do formato CSV para uma tabela em um banco temporário no seu MariaDB.
      4. Gerar um dump dos dados já migrados para o banco da clínica fictícia Medical Challenge.
*/

// Importação de Bibliotecas:
include "./lib.php";

// Conexão com o banco da clínica fictícia:
$connMedical = mysqli_connect("localhost", "root", "root", "MedicalChallenge")
  or die("Não foi possível conectar os servidor MySQL: MedicalChallenge\n");

// Conexão com o banco temporário:
$connTemp = mysqli_connect("localhost", "root", "root", "0temp")
  or die("Não foi possível conectar os servidor MySQL: 0temp\n");

// Informações de Inicio da Migração:
echo "Início da Migração: " . dateNow() . ".\n\n";

$host = "localhost";
$username = "root";
$password = "root"; 
$mysqli = new mysqli($host, $username, $password);

if ($mysqli->connect_error) {
    die("Erro de conexão: " . $mysqli->connect_error);
}

$medicalChallenge = "MedicalChallenge"; 


$sql = "CREATE DATABASE IF NOT EXISTS $medicalChallenge";

if ($mysqli->query($sql) === true) {
    echo "Banco de dados '$medicalChallenge' criado com sucesso.";
} else {
    echo "Erro ao criar o banco de dados: " . $mysqli->error;
}

function importSchedules($connTemp, $connMedical) {
  $query = "SELECT * FROM agendamentos";
  $result = mysqli_query($connTemp, $query);

 
  if (!$result) {
      die("Erro ao executar a consulta: " . $this->getMessage());
  }

  while ($row = mysqli_fetch_assoc($result)) {
    $descricao = mysqli_real_escape_string($connMedical, $row['descricao']);
    $dia = mysqli_real_escape_string($connMedical, $row['dia']);
    $hora_inicio = mysqli_real_escape_string($connMedical, $row['hora_inicio']);
    $hora_fim = mysqli_real_escape_string($connMedical, $row['hora_fim']);
    $cod_paciente = mysqli_real_escape_string($connMedical, $row['cod_paciente']);
    $cod_medico = mysqli_real_escape_string($connMedical, $row['cod_medico']);
    $medico = mysqli_real_escape_string($connMedical, $row['medico']);
    $cod_convenio = mysqli_real_escape_string($connMedical, $row['cod_convenio']);
    $convenio = mysqli_real_escape_string($connMedical, $row['convenio']);
    $procedimento = mysqli_real_escape_string($connMedical, $row['procedimento']);

    $sql = "INSERT INTO Agendamentos (descricao, dia, hora_inicio, hora_fim, cod_paciente, cod_medico, cod_convenio, procedimento)
                VALUES ('$descricao', '$dia', '$hora_inicio', '$hora_fim', '$cod_paciente', '$cod_medico', '$cod_convenio', '$procedimento')";

        $insert_result = mysqli_query($connMedical, $sql);

        if (!$insert_result) {
            die("Erro ao inserir dados na tabela Agendamentos do MedicalChallenge: " . mysqli_error($connMedical));
        }
  }
}

function importPatients($connTemp, $connMedical) {
  $query = "SELECT * FROM pacientes";
  $result = mysqli_query($connTemp, $query);

 
  if (!$result) {
      die("Erro ao executar a consulta: " . $this->getMessage());
  }

  while ($row = mysqli_fetch_assoc($result)) {
    $nome_paciente = mysqli_real_escape_string($connMedical, $row['nome_paciente']);
    $nasc_paciente = mysqli_real_escape_string($connMedical, $row['nasc_paciente']);
    $pai_paciente = mysqli_real_escape_string($connMedical, $row['pai_paciente']);
    $mae_paciente = mysqli_real_escape_string($connMedical, $row['mae_paciente']);
    $cpf_paciente = mysqli_real_escape_string($connMedical, $row['cpf_paciente']);
    $rg_paciente = mysqli_real_escape_string($connMedical, $row['rg_paciente']);
    $sexo_pac = mysqli_real_escape_string($connMedical, $row['sexo_pac']);
    $id_conv = mysqli_real_escape_string($connMedical, $row['id_conv']);
    $convenio = mysqli_real_escape_string($connMedical, $row['convenio']);
    $obs_clinicas = '';
    
    $sql = "INSERT INTO Pacientes (nome, data_nascimento, nome_pai, nome_mae, cpf, rg, sexo, cod_convenio, observacoes)
    VALUES ('$nome_paciente', '$nasc_paciente', '$pai_paciente', '$mae_paciente', '$cpf_paciente', '$rg_paciente', '$sexo_pac', '$id_conv', '$obs_clinicas')";

        $insert_result = mysqli_query($connMedical, $sql);

        if (!$insert_result) {
            die("Erro ao inserir dados na tabela Agendamentos do MedicalChallenge: " . mysqli_error($connMedical));
        }
  }
}


// Encerrando as conexões:
$connMedical->close();
$connTemp->close();
$mysqli->close();

// Informações de Fim da Migração:
echo "Fim da Migração: " . dateNow() . ".\n";


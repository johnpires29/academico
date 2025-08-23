<?php
/**
 * Controlador do Dashboard
 */

require_once APP_PATH . '/controllers/Controller.php';

class DashboardController extends Controller {
    /**
     * Construtor
     */
    public function __construct() {
        // Construtor vazio
    }
    
    /**
     * Exibe o dashboard de acordo com o perfil do usuário
     */
    public function index() {
        // Verifica se o usuário está autenticado
        if (!$this->requireAuth()) {
            return;
        }
        
        // Obtém o perfil do usuário
        $perfil = (int)$_SESSION['user_profile'];
        
        // Carrega os dados específicos para cada perfil
        $data = [];
        
        switch ($perfil) {
            case 1: // aluno
                $data = $this->getDadosAluno();
                break;
            case 2: // professor
                $data = $this->getDadosProfessor();
                break;
            case 3: // coordenador
                $data = $this->getDadosCoordenador();
                break;
            case 4: // secretario
                $data = $this->getDadosSecretario();
                break;
            case 5: // admin
                $data = $this->getDadosAdmin();
                break;
            default:
                // Perfil não reconhecido
                $this->redirect(base_url('auth/logout'));
                return;
        }
        
        // Adiciona dados comuns a todos os perfis
        $data['perfil'] = $perfil;
        $data['nome'] = $_SESSION['user_name'];
        
        // Carrega a view do dashboard
        $this->view('dashboard/index', $data);
    }
    
    /**
     * Obtém os dados para o dashboard do aluno
     * 
     * @return array Dados do dashboard
     */
    private function getDadosAluno() {
        // Aqui seriam carregados os dados específicos do aluno
        // como aulas recentes, próximas provas, tarefas pendentes, etc.
        
        // Por enquanto, retorna dados de exemplo
        return [
            'aulas_recentes' => [],
            'proximas_provas' => [],
            'tarefas_pendentes' => [],
            'boletim' => []
        ];
    }
    
    /**
     * Obtém os dados para o dashboard do professor
     * 
     * @return array Dados do dashboard
     */
    private function getDadosProfessor() {
        // Aqui seriam carregados os dados específicos do professor
        // como turmas, próximas aulas, provas a corrigir, etc.
        
        // Por enquanto, retorna dados de exemplo
        return [
            'turmas' => [],
            'proximas_aulas' => [],
            'provas_pendentes' => []
        ];
    }
    
    /**
     * Obtém os dados para o dashboard do coordenador
     * 
     * @return array Dados do dashboard
     */
    private function getDadosCoordenador() {
        // Aqui seriam carregados os dados específicos do coordenador
        // como professores, planos semestrais, etc.
        
        // Por enquanto, retorna dados de exemplo
        return [
            'professores' => [],
            'planos_semestrais' => []
        ];
    }
    
    /**
     * Obtém os dados para o dashboard do secretário
     * 
     * @return array Dados do dashboard
     */
    private function getDadosSecretario() {
        // Aqui seriam carregados os dados específicos do secretário
        // como alunos, professores, coordenadores, etc.
        
        // Por enquanto, retorna dados de exemplo
        return [
            'alunos' => [],
            'professores' => [],
            'coordenadores' => []
        ];
    }
    
    /**
     * Obtém os dados para o dashboard do administrador
     * 
     * @return array Dados do dashboard
     */
    private function getDadosAdmin() {
        // Aqui seriam carregados os dados específicos do administrador
        // como estatísticas gerais, usuários, etc.
        
        // Por enquanto, retorna dados de exemplo
        return [
            'estatisticas' => [],
            'usuarios' => []
        ];
    }
}
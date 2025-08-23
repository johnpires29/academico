<?php
/**
 * Funções auxiliares do sistema
 */

/**
 * Redireciona para uma URL específica
 * 
 * @param string $url URL para redirecionamento
 * @return void
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Retorna a URL base do sistema
 * 
 * @param string $path Caminho a ser anexado à URL base
 * @return string URL completa
 */
function base_url($path = '') {
    $base_url = 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/academico/public/';
    return $base_url . $path;
}

/**
 * Carrega uma view
 * 
 * @param string $view Nome da view a ser carregada
 * @param array $data Dados a serem passados para a view
 * @return void
 */
function view($view, $data = []) {
    // Extrair os dados para que fiquem disponíveis na view
    extract($data);
    
    // Caminho completo para o arquivo da view
    $viewFile = APP_PATH . '/views/' . $view . '.php';
    
    // Verificar se a view existe
    if (file_exists($viewFile)) {
        require_once $viewFile;
    } else {
        throw new Exception("View '{$view}' não encontrada.");
    }
}

/**
 * Sanitiza dados de entrada
 * 
 * @param mixed $data Dados a serem sanitizados
 * @return mixed Dados sanitizados
 */
function sanitize($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize($value);
        }
    } else {
        $data = htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    return $data;
}

/**
 * Verifica se o usuário está autenticado
 * 
 * @return bool True se autenticado, false caso contrário
 */
function is_authenticated() {
    return isset($_SESSION['user_id']);
}

/**
 * Verifica se o usuário tem permissão para acessar determinada área
 * 
 * @param string|array $profiles Perfil ou array de perfis permitidos
 * @return bool True se tem permissão, false caso contrário
 */
function has_permission($profiles) {
    if (!is_authenticated()) {
        return false;
    }
    
    if (!isset($_SESSION['user_profile'])) {
        return false;
    }
    
    // Converte o perfil para inteiro para garantir comparação correta
    $userProfile = (int)$_SESSION['user_profile'];
    
    if (is_array($profiles)) {
        // Converte cada perfil no array para inteiro
        $profilesInt = array_map(function($p) {
            return is_numeric($p) ? (int)$p : $p;
        }, $profiles);
        
        return in_array($userProfile, $profilesInt);
    }
    
    // Converte o perfil para inteiro se for numérico
    $profileInt = is_numeric($profiles) ? (int)$profiles : $profiles;
    
    return $userProfile === $profileInt;
}

/**
 * Gera um token CSRF
 * 
 * @return string Token CSRF
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Verifica se o token CSRF é válido
 * 
 * @param string $token Token a ser verificado
 * @return bool True se válido, false caso contrário
 */
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Formata uma data para o padrão brasileiro
 * 
 * @param string $date Data no formato Y-m-d
 * @return string Data no formato d/m/Y
 */
function format_date($date) {
    return date('d/m/Y', strtotime($date));
}

/**
 * Formata um valor para o padrão de moeda brasileira
 * 
 * @param float $value Valor a ser formatado
 * @return string Valor formatado
 */
function format_currency($value) {
    return 'R$ ' . number_format($value, 2, ',', '.');
}
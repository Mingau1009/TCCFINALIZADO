<?php
class Log {
    const USUARIO_PADRAO = 'academia@gmail.com';
    const MAX_TAMANHO_USER_AGENT = 255;
    
    public static function registrar($acao, $tabela_afetada = null, $registro_id = null, $dados_anteriores = null, $dados_novos = null) {
        try {
            // Verifica se a conexão está ativa
            $conexao = Db::conexao();
            if (!$conexao) {
                error_log("Falha na conexão com o banco de dados");
                return false;
            }

            $ip = self::getClientIp();
            $user_agent = self::sanitizeUserAgent($_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido');
            
            $dados_anteriores_json = self::formatDados($dados_anteriores);
            $dados_novos_json = self::formatDados($dados_novos);
            
            $sql = "INSERT INTO logs 
                    (usuario_email, acao, tabela_afetada, registro_id, 
                    dados_anteriores, dados_novos, ip, user_agent) 
                    VALUES 
                    (:usuario_email, :acao, :tabela_afetada, :registro_id, 
                    :dados_anteriores, :dados_novos, :ip, :user_agent)";
            
            $stmt = $conexao->prepare($sql);
            
            $stmt->bindValue(':usuario_email', self::USUARIO_PADRAO, PDO::PARAM_STR);
            $stmt->bindValue(':acao', $acao, PDO::PARAM_STR);
            $stmt->bindValue(':tabela_afetada', $tabela_afetada, PDO::PARAM_STR);
            $stmt->bindValue(':registro_id', $registro_id, is_int($registro_id) ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $stmt->bindValue(':dados_anteriores', $dados_anteriores_json, PDO::PARAM_STR);
            $stmt->bindValue(':dados_novos', $dados_novos_json, PDO::PARAM_STR);
            $stmt->bindValue(':ip', $ip, PDO::PARAM_STR);
            $stmt->bindValue(':user_agent', $user_agent, PDO::PARAM_STR);
            
            $result = $stmt->execute();
            
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("Erro ao executar query de log: " . print_r($errorInfo, true));
                return false;
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("Erro ao registrar log: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return false;
        }
    }
    
    private static function formatDados($dados) {
        if ($dados === null || $dados === '') {
            return null;
        }
        
        if (is_string($dados)) {
            json_decode($dados);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $dados;
            }
            return json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        
        return json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    
    private static function getClientIp() {
        $ip = 'Desconhecido';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : 'IP Inválido';
    }
    
    private static function sanitizeUserAgent($user_agent) {
        $user_agent = substr($user_agent, 0, self::MAX_TAMANHO_USER_AGENT);
        return filter_var($user_agent, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    }

    public static function registrarAulaAluno($acao, $evento_id, $aluno_ids = [], $dados_anteriores = null) {
        $dados_novos = null;
        
        if (!empty($aluno_ids)) {
            $dados_novos = [
                'evento_id' => $evento_id,
                'alunos' => $aluno_ids
            ];
        }
        
        return self::registrar(
            $acao,
            'evento_aluno',
            $evento_id,
            $dados_anteriores,
            $dados_novos
        );
    }
}
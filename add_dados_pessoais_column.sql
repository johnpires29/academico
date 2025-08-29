-- Script para adicionar a coluna dados_pessoais à tabela usuarios
USE academico;

-- Adicionar a coluna dados_pessoais se ela não existir
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS dados_pessoais TEXT DEFAULT NULL AFTER foto;
# Blog Generator - Gemini AI for WordPress

> Plugin WordPress para geração automatizada de conteúdo com IA

![Status](https://img.shields.io/badge/status-ativo-brightgreen)
![PHP](https://img.shields.io/badge/php-7.4%2B-blue)
![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue)

## Sobre o Projeto

Plugin profissional para WordPress que automatiza a criação de artigos de blog utilizando o Google Gemini AI. Desenvolvido com arquitetura limpa e foco em segurança, este projeto evoluiu do [protótipo Flask](https://github.com/jopaul0/BlogGenerator) para uma solução integrada ao WordPress.

## Funcionalidades

- **Geração Automatizada**: Criação completa de artigos (Título, Resumo, Conteúdo, SEO e Tags)
- **Arquitetura Desacoplada**: Separação entre interface (Admin) e processamento (Core/API)
- **Template JSON Customizável**: Alteração de tom de voz sem modificar código PHP
- **Integração SEO**: Suporte nativo para Yoast SEO e RankMath
- **Segurança**: Sanitização de dados e gerenciamento seguro de API Tokens

## Tecnologias

- **Backend**: PHP 7.4+
- **CMS**: WordPress 5.0+
- **IA**: Google Gemini API (2.5 Flash)
- **Ambiente**: Docker
- **Dados**: JSON para comunicação com IA

## Estrutura do Projeto
```
blog-generator/
├── admin/
│   ├── generator-page.php    # Interface de geração
│   └── settings-page.php     # Configurações
├── core/
│   └── class-api-gemini.php  # API e lógica de posts
└── blog-generator-main.php   # Arquivo principal
```

## Como Instalar

1. Clone o repositório em `wp-content/plugins/`
```bash
cd wp-content/plugins/
git clone https://github.com/jopaul0/blog-generator-wordpress.git
```

2. Ative o plugin no painel do WordPress

3. Configure em **Configurações > Gemini Config**:
   - Insira seu API Token do [Google AI Studio](https://makersuite.google.com/app/apikey)
   - Personalize o template JSON (opcional)

4. Acesse **Blog Generator** e gere seu primeiro artigo

## 📝 Projeto Base

Este plugin foi desenvolvido a partir do protótipo [BlogGenerator (Flask)](https://github.com/jopaul0/blog-generator-prototipo), adaptando a arquitetura para o ecossistema WordPress.

---

## Autor

**João Paulo Santos**

- LinkedIn: [João Paulo Santos](https://www.linkedin.com/in/joaosantos02/)
- Email: jopaulo.as8@gmail.com
- GitHub: [@jopaul0](https://github.com/jopaul0)

# Blog Generator - Gemini AI for WordPress

> Plugin WordPress para geração automatizada de conteúdo utilizando a inteligência artificial do Google Gemini.

![Status](https://img.shields.io/badge/status-v0.2-green)
![PHP](https://img.shields.io/badge/php-7.4%2B-blue)
![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue)

## 🚀 Sobre o Projeto (v0.2)

O **Blog Generator** é uma solução profissional para WordPress que automatiza a criação de artigos completos (Título, Conteúdo, Resumo, SEO e Tags) via Google Gemini API. A versão 0.2 introduz uma arquitetura **MVC (Model-View-Controller)** robusta e suporte inteligente a editores visuais.

---

## ✨ Novidades da Versão 0.2

- **Arquitetura MVC**: Separação clara de responsabilidades com as classes `ViewController`, `Builder` e `AI`.
- **Detecção Automática de Editor**: O plugin identifica se o **Elementor** está ativo e gera o post com widgets nativos do construtor. Caso contrário, utiliza o **Gutenberg**.
- **Integração SEO Avançada**: Suporte dinâmico para **Yoast SEO**, **RankMath** (com múltiplas palavras-chave) e **All in One SEO**.
- **Gestão de Segurança**: Implementação de *Nonces* do WordPress e sanitização rigorosa de inputs.
- **Localização (i18n)**: Suporte completo para traduções em Português, Inglês, Espanhol e Francês.

---

## 🛠️ Tecnologias e Arquitetura

- **Backend**: PHP 7.4+ orientado a objetos.
- **IA**: Google Gemini API (Modelos 1.5 Flash/Pro e 2.0 suportados via configurações).
- **Design Patterns**: Implementação inspirada no padrão Controller para manipulação de rotas `admin-post.php`.

---

## 📂 Estrutura de Pastas

```bash
gerador-gemini-onvale/
├── admin/               # Views (Interfaces do painel administrativo)
├── core/                # Lógica de Negócio (API, Construtor de Posts, AI)
├── languages/           # Arquivos de tradução (.po/.mo)
├── utils/               # Validadores e auxiliares
└── blog-generator-main.php # Inicializador do Plugin
```

---

## ⚙️ Como Instalar e Configurar

1. Faça o download do repositório e extraia em wp-content/plugins/blog-generator-gemini.
2. Ative o plugin no painel administrativo do WordPress.
3. Vá em Blog Generator > Settings:
    * Insira sua Gemini API Token obtida no Google AI Studio.
    * Escolha o modelo da IA (Flash é recomendado pela velocidade).
    * Configure a Persona e o Tom de Voz para alinhar a IA ao seu nicho.

---

## 📝 Funcionalidades de IA
O plugin instrui a IA a retornar um objeto JSON estrito contendo:

* Título H1 e Slug de URL otimizada.
* Blocos de artigo com formatação HTML (p, h2, h3).
* Tabelas comparativas automáticas em HTML/CSS.
* Resumo de 2 frases para o Excerpt do WordPress.
* Metadados de SEO completos.

---

## Projeto Base

Este plugin foi desenvolvido a partir do protótipo [BlogGenerator (Flask)](https://github.com/jopaul0/blog-generator-prototipo), adaptando a arquitetura para o ecossistema WordPress.

---

## Autor

**João Paulo Santos**

- LinkedIn: [João Paulo Santos](https://www.linkedin.com/in/joaosantos02/)
- Email: jopaulo.as8@gmail.com
- GitHub: [@jopaul0](https://github.com/jopaul0)

# Blog Generator - Gemini AI for WordPress

Este é um plugin profissional para WordPress desenvolvido para automatizar a criação de artigos de blog utilizando a inteligência artificial do Google Gemini. O plugin foi projetado com foco em arquitetura limpa, segurança e otimização de SEO para blogs corporativos.

## Funcionalidades

* **Geração Automatizada:** Criação de artigos completos (Título, Resumo, Conteúdo, SEO e Tags) a partir de um tema central.

* **Desacoplada:** Separação clara entre a lógica de interface (Admin) e o motor de processamento (Core/API).

* **Template JSON Customizável:** Permite alterar o tom de voz e as regras de negócio sem tocar no código PHP.

* **Integração com SEO:** Suporte nativo para metadados de SEO (Título SEO e Meta Description) compatíveis com plugins como Yoast SEO.

* **Segurança:** Uso de sanitização de dados e gerenciamento de API Tokens através da Options API do WordPress.

---

## Tecnologias Utilizadas

* **PHP:** Linguagem base do plugin.
* **WordPress Hooks API:** Gerenciamento de menus, ativação e inicialização.
* **Google Gemini API (2.5 Flash):** Motor de processamento de linguagem natural.
* **Docker:** Ambiente de desenvolvimento isolado.
* **JSON:** Formato de intercâmbio de dados entre a IA e o WordPress.

---

## Estrutura do Projeto

```bash
blog-generator/
├── admin/
│   ├── generator-page.php    # Interface de geração de conteúdo
│   └── settings-page.php     # Configurações de API e Template
├── core/
│   └── class-api-gemini.php  # Lógica de conexão e criação de posts
└── blog-generator-main.php   # Arquivo principal e ativação
```

---

## Como Instalar

1. Clone este repositório na pasta wp-content/plugins/ do seu WordPress.
2. Ative o plugin através do painel administrativo do WordPress.
3. Acesse Configurações > Gemini Config e insira seu API Token do Google AI Studio.
4. No menu Blog Generator, insira o tema desejado e clique em "Generate Article".

---

## Autor

Desenvolvido por **João Paulo Santos**
- **LinkedIn**: [João Paulo Santos](https://www.linkedin.com/in/joaosantos02/)
- **Email**: [jopaulo.as8@gmail.com](mailto:jopaulo.as8@gmail.com)
- **GitHub**: [@jopaul0](https://github.com/jopaul0)

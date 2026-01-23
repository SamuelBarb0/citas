<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteContent;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $contents = [
            // ========== HERO SECTION ==========
            [
                'key' => 'hero_title_1',
                'section' => 'hero',
                'label' => 'Título línea 1',
                'type' => 'text',
                'default_value' => 'Citas, contactos',
                'order' => 1,
            ],
            [
                'key' => 'hero_title_2',
                'section' => 'hero',
                'label' => 'Título línea 2',
                'type' => 'text',
                'default_value' => 'y amor en',
                'order' => 2,
            ],
            [
                'key' => 'hero_title_highlight',
                'section' => 'hero',
                'label' => 'Título destacado (gradiente)',
                'type' => 'text',
                'default_value' => 'Mallorca',
                'order' => 3,
            ],
            [
                'key' => 'hero_subtitle',
                'section' => 'hero',
                'label' => 'Subtítulo',
                'type' => 'textarea',
                'default_value' => 'Encuentra gente con tus mismas ganas de compartir momentos en la isla',
                'order' => 4,
            ],
            [
                'key' => 'hero_btn_register',
                'section' => 'hero',
                'label' => 'Botón de registro',
                'type' => 'text',
                'default_value' => 'Crear mi perfil gratis',
                'order' => 5,
            ],
            [
                'key' => 'hero_btn_login',
                'section' => 'hero',
                'label' => 'Botón de login',
                'type' => 'text',
                'default_value' => 'Entrar mi perfil',
                'order' => 6,
            ],
            [
                'key' => 'hero_form_title',
                'section' => 'hero',
                'label' => 'Título del formulario',
                'type' => 'text',
                'default_value' => 'Crear mi perfil',
                'order' => 7,
            ],
            [
                'key' => 'hero_form_btn',
                'section' => 'hero',
                'label' => 'Botón del formulario',
                'type' => 'text',
                'default_value' => 'Empezar ahora',
                'order' => 8,
            ],

            // ========== SECCIÓN PERFILES ==========
            [
                'key' => 'profiles_title',
                'section' => 'profiles',
                'label' => 'Título sección perfiles',
                'type' => 'text',
                'default_value' => 'Conoce gente increíble',
                'order' => 1,
            ],
            [
                'key' => 'profiles_subtitle',
                'section' => 'profiles',
                'label' => 'Subtítulo sección perfiles',
                'type' => 'textarea',
                'default_value' => 'Miles de personas en Mallorca ya están conectando. ¡Únete ahora!',
                'order' => 2,
            ],
            [
                'key' => 'profiles_btn_more',
                'section' => 'profiles',
                'label' => 'Botón ver más perfiles',
                'type' => 'text',
                'default_value' => 'Ver más perfiles',
                'order' => 3,
            ],

            // ========== SECCIÓN CÓMO FUNCIONA ==========
            [
                'key' => 'features_title',
                'section' => 'features',
                'label' => 'Título sección',
                'type' => 'text',
                'default_value' => '¿Cómo funciona?',
                'order' => 1,
            ],
            [
                'key' => 'feature1_emoji',
                'section' => 'features',
                'label' => 'Paso 1 - Emoji',
                'type' => 'text',
                'default_value' => '👤',
                'order' => 2,
            ],
            [
                'key' => 'feature1_title',
                'section' => 'features',
                'label' => 'Paso 1 - Título',
                'type' => 'text',
                'default_value' => 'Crea tu perfil',
                'order' => 3,
            ],
            [
                'key' => 'feature1_desc',
                'section' => 'features',
                'label' => 'Paso 1 - Descripción',
                'type' => 'textarea',
                'default_value' => 'Regístrate gratis y completa tu perfil con tus fotos y preferencias.',
                'order' => 4,
            ],
            [
                'key' => 'feature2_emoji',
                'section' => 'features',
                'label' => 'Paso 2 - Emoji',
                'type' => 'text',
                'default_value' => '💕',
                'order' => 5,
            ],
            [
                'key' => 'feature2_title',
                'section' => 'features',
                'label' => 'Paso 2 - Título',
                'type' => 'text',
                'default_value' => 'Encuentra matches',
                'order' => 6,
            ],
            [
                'key' => 'feature2_desc',
                'section' => 'features',
                'label' => 'Paso 2 - Descripción',
                'type' => 'textarea',
                'default_value' => 'Descubre perfiles de personas auténticas en Mallorca.',
                'order' => 7,
            ],
            [
                'key' => 'feature3_emoji',
                'section' => 'features',
                'label' => 'Paso 3 - Emoji',
                'type' => 'text',
                'default_value' => '💬',
                'order' => 8,
            ],
            [
                'key' => 'feature3_title',
                'section' => 'features',
                'label' => 'Paso 3 - Título',
                'type' => 'text',
                'default_value' => 'Conversa y conoce',
                'order' => 9,
            ],
            [
                'key' => 'feature3_desc',
                'section' => 'features',
                'label' => 'Paso 3 - Descripción',
                'type' => 'textarea',
                'default_value' => 'Cuando haya match, podrás chatear y quedar en persona.',
                'order' => 10,
            ],

            // ========== SECCIÓN SEGURIDAD ==========
            [
                'key' => 'safety_title',
                'section' => 'safety',
                'label' => 'Título sección seguridad',
                'type' => 'text',
                'default_value' => 'Consejos de seguridad para tus interacciones',
                'order' => 1,
            ],
            [
                'key' => 'safety_subtitle',
                'section' => 'safety',
                'label' => 'Subtítulo sección seguridad',
                'type' => 'textarea',
                'default_value' => 'En Citas Mallorca te recomendamos cuidar tu privacidad y seguridad.',
                'order' => 2,
            ],
            [
                'key' => 'safety_tip1',
                'section' => 'safety',
                'label' => 'Consejo 1',
                'type' => 'textarea',
                'default_value' => 'No compartas datos personales sensibles (dirección, documentos, números de tarjetas).',
                'order' => 3,
            ],
            [
                'key' => 'safety_tip2',
                'section' => 'safety',
                'label' => 'Consejo 2',
                'type' => 'textarea',
                'default_value' => 'Mantén la conversación dentro de la plataforma hasta sentir confianza.',
                'order' => 4,
            ],
            [
                'key' => 'safety_tip3',
                'section' => 'safety',
                'label' => 'Consejo 3',
                'type' => 'textarea',
                'default_value' => 'Si decides quedar, elige un lugar público y avisa a alguien.',
                'order' => 5,
            ],
            [
                'key' => 'safety_tip4',
                'section' => 'safety',
                'label' => 'Consejo 4',
                'type' => 'textarea',
                'default_value' => 'No aceptes presiones para enviar fotos privadas o dinero.',
                'order' => 6,
            ],
            [
                'key' => 'safety_tip5',
                'section' => 'safety',
                'label' => 'Consejo 5',
                'type' => 'textarea',
                'default_value' => 'Si notas comportamientos sospechosos, repórtalo de inmediato.',
                'order' => 7,
            ],
            [
                'key' => 'safety_footer',
                'section' => 'safety',
                'label' => 'Mensaje de cierre',
                'type' => 'textarea',
                'default_value' => 'Tu bienestar es lo más importante. Conecta con seguridad.',
                'order' => 8,
            ],
            [
                'key' => 'safety_report_title',
                'section' => 'safety',
                'label' => 'Título reporte',
                'type' => 'text',
                'default_value' => 'Reporta comportamientos sospechosos:',
                'order' => 9,
            ],
            [
                'key' => 'safety_report_text',
                'section' => 'safety',
                'label' => 'Texto reporte',
                'type' => 'textarea',
                'default_value' => 'Si ves falta de respeto, presiones, chantajes o cualquier situación fraudulenta, escríbenos a info@citasmallorca.es. Nuestro equipo actúa rápido para proteger a la comunidad.',
                'order' => 10,
            ],
            [
                'key' => 'safety_enjoy',
                'section' => 'safety',
                'label' => 'Mensaje final',
                'type' => 'text',
                'default_value' => 'Disfruta con responsabilidad',
                'order' => 11,
            ],

            // ========== SECCIÓN CTA FINAL ==========
            [
                'key' => 'cta_line1',
                'section' => 'cta',
                'label' => 'CTA línea 1',
                'type' => 'text',
                'default_value' => 'Encuentra gente',
                'order' => 1,
            ],
            [
                'key' => 'cta_line2',
                'section' => 'cta',
                'label' => 'CTA línea 2',
                'type' => 'text',
                'default_value' => 'con tus mismas',
                'order' => 2,
            ],
            [
                'key' => 'cta_line3',
                'section' => 'cta',
                'label' => 'CTA línea 3 (gradiente)',
                'type' => 'text',
                'default_value' => 'ganas de compartir',
                'order' => 3,
            ],
            [
                'key' => 'cta_line4',
                'section' => 'cta',
                'label' => 'CTA línea 4 (gradiente)',
                'type' => 'text',
                'default_value' => 'momentos en',
                'order' => 4,
            ],
            [
                'key' => 'cta_line5',
                'section' => 'cta',
                'label' => 'CTA línea 5 (gradiente)',
                'type' => 'text',
                'default_value' => 'la isla.',
                'order' => 5,
            ],

            // ========== GENERAL ==========
            [
                'key' => 'contact_email',
                'section' => 'general',
                'label' => 'Email de contacto',
                'type' => 'text',
                'default_value' => 'info@citasmallorca.es',
                'order' => 1,
            ],
        ];

        foreach ($contents as $content) {
            SiteContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }
    }
}

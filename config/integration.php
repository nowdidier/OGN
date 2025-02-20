<?php
/*
 * Индекгация, авторизация и др., через социальные службы (сайты)...
 * Indexing, authorization, etc., through social services (websites)...
 */

return [

    // If smtp is enabled - true, then fill in the settings at the bottom
    // Если smtp включен - true, то заполните настройки вниэу
    'smtp'      => true,
    'smtp_user' => 'nowdidier@gmail.com',
    'smtp_pass' => 'your-app-specific-password',
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 587,

    // Captcha. Using hCaptcha's free tier
    // Капча. Использование бесплатного уровня hCaptcha
    'captcha'               => true, 
    'captcha_public_key'    => '10000000-ffff-ffff-ffff-000000000001', // hCaptcha site key
    'captcha_private_key'   => '0x0000000000000000000000000000000000000000', // hCaptcha secret key
    
    // Discord WEBHOOK URL
    // For Discord server settings, go to the Webhooks section and create a new one 
    // Для настроек сервера Discord зайти в раздел Вебхуки и создайте новый 
    'discord'               => false,
    'discord_webhook_url'   => 'https://discord.com/api/webhooks/***',
    'discord_name_bot'      => 'PostBot',
    'discord_icon_url'      => 'https://cdn.discordapp.com/avatars/***.png',

    // For sending settings to Telegram
    // Для настроек отправки в Telegram 
    'telegram'              => false,
    'telegram_token'        => '******:******',
    'telegram_chat_id'      => '******',

    // Screenshots of the service https://screenshotone.com/
    // Скриншоты сервиса https://screenshotone.com/
    'sc_access_key'         => 'V8ETGePv2BrfXQ',
    'sc_secret_key'         => 'GQ31WGuSXfyfSQ',
    
    // Enable spam detection (email) on stopforumspam.com
    // Включить определение спама (email) по stopforumspam.com 
    'stopforumspam'         => false,
];

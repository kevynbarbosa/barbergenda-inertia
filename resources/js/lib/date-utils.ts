/**
 * Utilitários para formatação de datas
 */

/**
 * Formata uma data para o formato brasileiro (DD/MM/AAAA)
 */
export const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

/**
 * Formata uma data e hora para o formato brasileiro (DD/MM/AAAA HH:mm)
 */
export const formatDateTime = (dateString: string): string => {
    return new Date(dateString).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

/**
 * Formata uma data de forma relativa (ex: "há 2 dias", "ontem", "hoje")
 */
export const formatRelativeDate = (dateString: string): string => {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);

    const intervals = {
        ano: 31536000,
        mês: 2592000,
        semana: 604800,
        dia: 86400,
        hora: 3600,
        minuto: 60,
    };

    for (const [unit, seconds] of Object.entries(intervals)) {
        const interval = Math.floor(diffInSeconds / seconds);
        if (interval >= 1) {
            return `há ${interval} ${unit}${interval > 1 ? (unit === 'mês' ? 'es' : 's') : ''}`;
        }
    }

    return 'agora mesmo';
};

/**
 * Verifica se uma data é hoje
 */
export const isToday = (dateString: string): boolean => {
    const date = new Date(dateString);
    const today = new Date();

    return date.getDate() === today.getDate() &&
           date.getMonth() === today.getMonth() &&
           date.getFullYear() === today.getFullYear();
};

/**
 * Verifica se uma data é ontem
 */
export const isYesterday = (dateString: string): boolean => {
    const date = new Date(dateString);
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);

    return date.getDate() === yesterday.getDate() &&
           date.getMonth() === yesterday.getMonth() &&
           date.getFullYear() === yesterday.getFullYear();
};
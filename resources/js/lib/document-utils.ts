/**
 * Formata um documento (CPF ou CNPJ) aplicando a máscara apropriada
 * A função identifica automaticamente o tipo baseado no comprimento
 *
 * @param document - String com apenas números do documento
 * @returns Documento formatado com máscara ou o valor original se inválido
 */
export function formatDocument(document: string): string {
    // Remove todos os caracteres que não são números
    const numbersOnly = document.replace(/\D/g, '');

    // Se não há números, retorna o valor original
    if (!numbersOnly) {
        return document;
    }

    // Identifica o tipo baseado no comprimento
    if (numbersOnly.length === 11) {
        // CPF: 000.000.000-00
        return numbersOnly.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    } else if (numbersOnly.length === 14) {
        // CNPJ: 00.000.000/0000-00
        return numbersOnly.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    }

    // Se não é CPF nem CNPJ válido, retorna apenas os números
    return numbersOnly;
}

/**
 * Identifica o tipo de documento baseado no comprimento
 *
 * @param document - String com o documento (com ou sem máscara)
 * @returns 'cpf', 'cnpj' ou 'unknown'
 */
export function getDocumentType(document: string): 'cpf' | 'cnpj' | 'unknown' {
    const numbersOnly = document.replace(/\D/g, '');

    if (numbersOnly.length === 11) {
        return 'cpf';
    } else if (numbersOnly.length === 14) {
        return 'cnpj';
    }

    return 'unknown';
}

/**
 * Obtém o rótulo em português para o tipo de documento
 *
 * @param document - String com o documento
 * @returns 'CPF', 'CNPJ' ou 'Documento'
 */
export function getDocumentLabel(document: string): string {
    const type = getDocumentType(document);

    const labels = {
        cpf: 'CPF',
        cnpj: 'CNPJ',
        unknown: 'Documento'
    };

    return labels[type];
}
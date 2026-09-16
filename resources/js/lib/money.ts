export function formatGs(value: number | string | null | undefined): string {
    if (value === null || value === undefined) return "Gs. 0";
    return (
        "Gs. " +
        new Intl.NumberFormat("es-PY", {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(Number(value))
    );
}

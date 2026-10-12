export default () => ({
    open: false,
    title: 'Generar Ticket de Soporte',
    openModal() {
        this.open = true;
    },
    closeModal() {
        this.open = false;
    }
});

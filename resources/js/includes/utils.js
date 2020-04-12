let methods = {
    async waitWhile(condition, callback, time = 10) {
        while(eval(condition)) {
            await new Promise(resolve => setTimeout(resolve, time));
        }
        callback();
    },
    mergeExistingFields(source, fields) {
        return _.assign(_.cloneDeep(source), _.pick(fields, _.keys(source)));
    },
    resetForm() {
        this.form = _.cloneDeep(this.originForm);
        this.errors.clear();
    },
};

export default methods;

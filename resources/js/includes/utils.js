let methods = {
    async waitWhile(condition, callback, time = 10) {
        while(eval(condition)) {
            await new Promise(resolve => setTimeout(resolve, time));
        }
        callback();
    },
};

export default methods;

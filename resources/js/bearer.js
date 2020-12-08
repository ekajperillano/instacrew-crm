module.exports = {
    request: function (req, token) {
        if (req.url === this.options.refreshData.url) {
            req.data = {refresh_token: this.token(this.options.refreshTokenName)}
        }
        this.options.http._setHeaders.call(this, req, {Authorization: 'Bearer ' + token});
    },
    
    response: function (res) {
        const resData = res.data || {}

        var headers = this.options.http._getHeaders.call(this, res),
            token = headers.Authorization || headers.authorization;

        if (token) {
            token = token.split(/Bearer\:?\s?/i);
            
            return token[token.length > 1 ? 1 : 0].trim();
        }
    }
};
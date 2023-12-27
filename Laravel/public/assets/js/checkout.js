const Checkout = (function () {
    let instance;

    function loadAjax(url, onLoaded, onError = null) {
        const http = new XMLHttpRequest();
        http.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                const data = JSON.parse(this.responseText);
                onLoaded(data['data']);
            } else {
                if (onError) {
                    onError(this.status)
                }
            }
        };
        http.open("GET", url, true);
        http.send(null);
    }

    function init() {
        return {
            loadAllDistricts: (provinceId, onLoaded) => {
                loadAjax(`/districts/${provinceId}`, (data) => {
                    onLoaded(data);
                });
            },

            loadAllWards: (districtId, onLoaded) => {
                loadAjax(`/wards/${districtId}`, (data) => {
                    onLoaded(data);
                });
            },

            getShipFee(provinceId, districtId, wardId, onLoaded) {
                const url = `/getFee?province_id=${provinceId}&district_id=${districtId}&ward_id=${wardId}`;
                loadAjax(url, (data) => {
                    onLoaded(data);
                });
            },

            getVoucher(code, onLoaded) {
                const url = `/voucher/check/${code}`;
                loadAjax(url, (data) => {
                    onLoaded(data);
                });
            },

            getWarehouse(productId, sizeId, colorId, onLoaded) {
                const url = `/getWarehouse?product_id=${productId}&size_id=${sizeId}&color_id=${colorId}`;
                loadAjax(url, (data) => {
                    onLoaded(data);
                });
            },
        }
    }

    return {
        getInstance: function () {
            if (!instance) instance = init();
            return instance;
        }
    }
})();
window.Checkout = Checkout.getInstance();

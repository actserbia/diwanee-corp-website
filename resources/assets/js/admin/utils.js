$(document).ready(function() {
    var Utils = {};
    Utils.inArray = function(searchFor, property) {
        var retVal = -1;
        var self = this;
        for(var index = 0; index < self.length; index++){
            var item = self[index];
            if (item.hasOwnProperty(property)) {
                if (item[property] == searchFor) {
                    return index;
                }
            }
        };
        return retVal;
    };
    Array.prototype.inArray = Utils.inArray;
});
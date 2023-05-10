(function() {
  'use strict';

  var SelectCells = (function() {
    var __ = function() {
      this.lastY = 0;
      this.lastX = 0;
      this.items = document.querySelectorAll('[tvtype="selectcells"]');
      this.el = null;
      this.selected = null;
      var _this = this;
      for (var i = 0; i < this.items.length; i++) {
        this.items[i].onfocus = function(e) {
          _this.el = e.target;
          _this.onfocus.call(_this, e.target);
        };
        this.items[i].onblur = function(e) {
          _this.onblur.call(_this, e.target);
        };
      }
    };

    __.prototype.onfocus = function(el) {
      var _this = this;
      el.insertAdjacentHTML('afterend', this.grid());
      this.container = el.nextElementSibling;
      var _ = el.value.split('*');
      this.hover(el, (parseInt(_[0]) - 1) || 0, (parseInt(_[1]) - 1) || 0);
      this.container.onmousemove = function(e) {
        _this.onmousemove.call(_this, e);
      };
    };

    __.prototype.onblur = function() {
      if (this.container) {
        this.container.parentElement.removeChild(this.container);
      }
    };

    __.prototype.hover = function(a, b, c) {
      var e, f, g, h, i, j = this.container.getElementsByTagName('table')[0];
      for (this.el.value = b + 1 + '*' + (c + 1), f = 0; f < 12; f++) {
        for (e = 0; e < 12; e++) {
          h = j.rows[f].childNodes[e].firstChild, i = (e <= b) && f <= c, (i ? h.classList.add('mce-active') : h.classList.remove('mce-active')), i && (g = h);
        }
      }
      this.selected = g;
    };

    __.prototype.onmousemove = function(a) {
      var b, c, d = a.target;
      'A' === d.tagName.toUpperCase() && (b = parseInt(d.getAttribute('data-mce-x'), 10), c = parseInt(d.getAttribute('data-mce-y'), 10), b === this.lastX && c === this.lastY || (this.hover.call(this, i, b, c), this.lastX = b, this.lastY = c));
    };

    __.prototype.grid = function() {
      var a = '<div class="selectcells"><table role="grid" class="mce-grid mce-grid-border" aria-readonly="true">';
      for (var b = 0; b < 12; b++) {
        a += '<tr>';
        for (var c = 0; c < 12; c++) {
          a += '<td role="gridcell" tabindex="-1"><a id="mcegrid' + (10 * b + c) + '" href="#" data-mce-x="' + c + '" data-mce-y="' + b + '"></a></td>';
        }
        a += '</tr>';
      }
      return a += '</table></div>';
    };

    return new __();
  });

  document.addEventListener('DOMContentLoaded', function() {
    var selectcells = new SelectCells();
  });
})();
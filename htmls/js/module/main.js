/* Zepto 1.0 - polyfill zepto detect event ajax form fx - zeptojs.com/license */




;(function(undefined){
  if (String.prototype.trim === undefined) // fix for iOS 3.2
    String.prototype.trim = function(){ return this.replace(/^\s+|\s+$/g, '') }

  // For iOS 3.x
  // from https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Array/reduce
  if (Array.prototype.reduce === undefined)
    Array.prototype.reduce = function(fun){
      if(this === void 0 || this === null) throw new TypeError()
      var t = Object(this), len = t.length >>> 0, k = 0, accumulator
      if(typeof fun != 'function') throw new TypeError()
      if(len == 0 && arguments.length == 1) throw new TypeError()

      if(arguments.length >= 2)
       accumulator = arguments[1]
      else
        do{
          if(k in t){
            accumulator = t[k++]
            break
          }
          if(++k >= len) throw new TypeError()
        } while (true)

      while (k < len){
        if(k in t) accumulator = fun.call(undefined, accumulator, t[k], k, t)
        k++
      }
      return accumulator
    }

})()





var Zepto = (function() {
  var undefined, key, $, classList, emptyArray = [], slice = emptyArray.slice, filter = emptyArray.filter,
    document = window.document,
    elementDisplay = {}, classCache = {},
    getComputedStyle = document.defaultView.getComputedStyle,
    cssNumber = { 'column-count': 1, 'columns': 1, 'font-weight': 1, 'line-height': 1,'opacity': 1, 'z-index': 1, 'zoom': 1 },
    fragmentRE = /^\s*<(\w+|!)[^>]*>/,
    tagExpanderRE = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/ig,
    rootNodeRE = /^(?:body|html)$/i,

    // special attributes that should be get/set via method calls
    methodAttributes = ['val', 'css', 'html', 'text', 'data', 'width', 'height', 'offset'],

    adjacencyOperators = [ 'after', 'prepend', 'before', 'append' ],
    table = document.createElement('table'),
    tableRow = document.createElement('tr'),
    containers = {
      'tr': document.createElement('tbody'),
      'tbody': table, 'thead': table, 'tfoot': table,
      'td': tableRow, 'th': tableRow,
      '*': document.createElement('div')
    },
    readyRE = /complete|loaded|interactive/,
    classSelectorRE = /^\.([\w-]+)$/,
    idSelectorRE = /^#([\w-]*)$/,
    tagSelectorRE = /^[\w-]+$/,
    class2type = {},
    toString = class2type.toString,
    zepto = {},
    camelize, uniq,
    tempParent = document.createElement('div')

  zepto.matches = function(element, selector) {
    if (!element || element.nodeType !== 1) return false
    var matchesSelector = element.webkitMatchesSelector || element.mozMatchesSelector ||
                          element.oMatchesSelector || element.matchesSelector
    if (matchesSelector) return matchesSelector.call(element, selector)
    // fall back to performing a selector:
    var match, parent = element.parentNode, temp = !parent
    if (temp) (parent = tempParent).appendChild(element)
    match = ~zepto.qsa(parent, selector).indexOf(element)
    temp && tempParent.removeChild(element)
    return match
  }

  function type(obj) {
    return obj == null ? String(obj) :
      class2type[toString.call(obj)] || "object"
  }

  function isFunction(value) { return type(value) == "function" }
  function isWindow(obj)     { return obj != null && obj == obj.window }
  function isDocument(obj)   { return obj != null && obj.nodeType == obj.DOCUMENT_NODE }
  function isObject(obj)     { return type(obj) == "object" }
  function isPlainObject(obj) {
    return isObject(obj) && !isWindow(obj) && obj.__proto__ == Object.prototype
  }
  function isArray(value) { return value instanceof Array }
  function likeArray(obj) { return typeof obj.length == 'number' }

  function compact(array) { return filter.call(array, function(item){ return item != null }) }
  function flatten(array) { return array.length > 0 ? $.fn.concat.apply([], array) : array }
  camelize = function(str){ return str.replace(/-+(.)?/g, function(match, chr){ return chr ? chr.toUpperCase() : '' }) }
  function dasherize(str) {
    return str.replace(/::/g, '/')
           .replace(/([A-Z]+)([A-Z][a-z])/g, '$1_$2')
           .replace(/([a-z\d])([A-Z])/g, '$1_$2')
           .replace(/_/g, '-')
           .toLowerCase()
  }
  uniq = function(array){ return filter.call(array, function(item, idx){ return array.indexOf(item) == idx }) }

  function classRE(name) {
    return name in classCache ?
      classCache[name] : (classCache[name] = new RegExp('(^|\\s)' + name + '(\\s|$)'))
  }

  function maybeAddPx(name, value) {
    return (typeof value == "number" && !cssNumber[dasherize(name)]) ? value + "px" : value
  }

  function defaultDisplay(nodeName) {
    var element, display
    if (!elementDisplay[nodeName]) {
      element = document.createElement(nodeName)
      document.body.appendChild(element)
      display = getComputedStyle(element, '').getPropertyValue("display")
      element.parentNode.removeChild(element)
      display == "none" && (display = "block")
      elementDisplay[nodeName] = display
    }
    return elementDisplay[nodeName]
  }

  function children(element) {
    return 'children' in element ?
      slice.call(element.children) :
      $.map(element.childNodes, function(node){ if (node.nodeType == 1) return node })
  }

  // `$.zepto.fragment` takes a html string and an optional tag name
  // to generate DOM nodes nodes from the given html string.
  // The generated DOM nodes are returned as an array.
  // This function can be overriden in plugins for example to make
  // it compatible with browsers that don't support the DOM fully.
  zepto.fragment = function(html, name, properties) {
    if (html.replace) html = html.replace(tagExpanderRE, "<$1></$2>")
    if (name === undefined) name = fragmentRE.test(html) && RegExp.$1
    if (!(name in containers)) name = '*'

    var nodes, dom, container = containers[name]
    container.innerHTML = '' + html
    dom = $.each(slice.call(container.childNodes), function(){
      container.removeChild(this)
    })
    if (isPlainObject(properties)) {
      nodes = $(dom)
      $.each(properties, function(key, value) {
        if (methodAttributes.indexOf(key) > -1) nodes[key](value)
        else nodes.attr(key, value)
      })
    }
    return dom
  }

  // `$.zepto.Z` swaps out the prototype of the given `dom` array
  // of nodes with `$.fn` and thus supplying all the Zepto functions
  // to the array. Note that `__proto__` is not supported on Internet
  // Explorer. This method can be overriden in plugins.
  zepto.Z = function(dom, selector) {
    dom = dom || []
    dom.__proto__ = $.fn
    dom.selector = selector || ''
    return dom
  }

  // `$.zepto.isZ` should return `true` if the given object is a Zepto
  // collection. This method can be overriden in plugins.
  zepto.isZ = function(object) {
    return object instanceof zepto.Z
  }

  // `$.zepto.init` is Zepto's counterpart to jQuery's `$.fn.init` and
  // takes a CSS selector and an optional context (and handles various
  // special cases).
  // This method can be overriden in plugins.
  zepto.init = function(selector, context) {
    // If nothing given, return an empty Zepto collection
    if (!selector) return zepto.Z()
    // If a function is given, call it when the DOM is ready
    else if (isFunction(selector)) return $(document).ready(selector)
    // If a Zepto collection is given, juts return it
    else if (zepto.isZ(selector)) return selector
    else {
      var dom
      // normalize array if an array of nodes is given
      if (isArray(selector)) dom = compact(selector)
      // Wrap DOM nodes. If a plain object is given, duplicate it.
      else if (isObject(selector))
        dom = [isPlainObject(selector) ? $.extend({}, selector) : selector], selector = null
      // If it's a html fragment, create nodes from it
      else if (fragmentRE.test(selector))
        dom = zepto.fragment(selector.trim(), RegExp.$1, context), selector = null
      // If there's a context, create a collection on that context first, and select
      // nodes from there
      else if (context !== undefined) return $(context).find(selector)
      // And last but no least, if it's a CSS selector, use it to select nodes.
      else dom = zepto.qsa(document, selector)
      // create a new Zepto collection from the nodes found
      return zepto.Z(dom, selector)
    }
  }

  // `$` will be the base `Zepto` object. When calling this
  // function just call `$.zepto.init, which makes the implementation
  // details of selecting nodes and creating Zepto collections
  // patchable in plugins.
  $ = function(selector, context){
    return zepto.init(selector, context)
  }

  function extend(target, source, deep) {
    for (key in source)
      if (deep && (isPlainObject(source[key]) || isArray(source[key]))) {
        if (isPlainObject(source[key]) && !isPlainObject(target[key]))
          target[key] = {}
        if (isArray(source[key]) && !isArray(target[key]))
          target[key] = []
        extend(target[key], source[key], deep)
      }
      else if (source[key] !== undefined) target[key] = source[key]
  }

  // Copy all but undefined properties from one or more
  // objects to the `target` object.
  $.extend = function(target){
    var deep, args = slice.call(arguments, 1)
    if (typeof target == 'boolean') {
      deep = target
      target = args.shift()
    }
    args.forEach(function(arg){ extend(target, arg, deep) })
    return target
  }

  // `$.zepto.qsa` is Zepto's CSS selector implementation which
  // uses `document.querySelectorAll` and optimizes for some special cases, like `#id`.
  // This method can be overriden in plugins.
  zepto.qsa = function(element, selector){
    var found
    return (isDocument(element) && idSelectorRE.test(selector)) ?
      ( (found = element.getElementById(RegExp.$1)) ? [found] : [] ) :
      (element.nodeType !== 1 && element.nodeType !== 9) ? [] :
      slice.call(
        classSelectorRE.test(selector) ? element.getElementsByClassName(RegExp.$1) :
        tagSelectorRE.test(selector) ? element.getElementsByTagName(selector) :
        element.querySelectorAll(selector)
      )
  }

  function filtered(nodes, selector) {
    return selector === undefined ? $(nodes) : $(nodes).filter(selector)
  }

  $.contains = function(parent, node) {
    return parent !== node && parent.contains(node)
  }

  function funcArg(context, arg, idx, payload) {
    return isFunction(arg) ? arg.call(context, idx, payload) : arg
  }

  function setAttribute(node, name, value) {
    value == null ? node.removeAttribute(name) : node.setAttribute(name, value)
  }

  // access className property while respecting SVGAnimatedString
  function className(node, value){
    var klass = node.className,
        svg   = klass && klass.baseVal !== undefined

    if (value === undefined) return svg ? klass.baseVal : klass
    svg ? (klass.baseVal = value) : (node.className = value)
  }

  // "true"  => true
  // "false" => false
  // "null"  => null
  // "42"    => 42
  // "42.5"  => 42.5
  // JSON    => parse if valid
  // String  => self
  function deserializeValue(value) {
    var num
    try {
      return value ?
        value == "true" ||
        ( value == "false" ? false :
          value == "null" ? null :
          !isNaN(num = Number(value)) ? num :
          /^[\[\{]/.test(value) ? $.parseJSON(value) :
          value )
        : value
    } catch(e) {
      return value
    }
  }

  $.type = type
  $.isFunction = isFunction
  $.isWindow = isWindow
  $.isArray = isArray
  $.isPlainObject = isPlainObject

  $.isEmptyObject = function(obj) {
    var name
    for (name in obj) return false
    return true
  }

  $.inArray = function(elem, array, i){
    return emptyArray.indexOf.call(array, elem, i)
  }

  $.camelCase = camelize
  $.trim = function(str) { return str.trim() }

  // plugin compatibility
  $.uuid = 0
  $.support = { }
  $.expr = { }

  $.map = function(elements, callback){
    var value, values = [], i, key
    if (likeArray(elements))
      for (i = 0; i < elements.length; i++) {
        value = callback(elements[i], i)
        if (value != null) values.push(value)
      }
    else
      for (key in elements) {
        value = callback(elements[key], key)
        if (value != null) values.push(value)
      }
    return flatten(values)
  }

  $.each = function(elements, callback){
    var i, key
    if (likeArray(elements)) {
      for (i = 0; i < elements.length; i++)
        if (callback.call(elements[i], i, elements[i]) === false) return elements
    } else {
      for (key in elements)
        if (callback.call(elements[key], key, elements[key]) === false) return elements
    }

    return elements
  }

  $.grep = function(elements, callback){
    return filter.call(elements, callback)
  }

  if (window.JSON) $.parseJSON = JSON.parse

  // Populate the class2type map
  $.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(i, name) {
    class2type[ "[object " + name + "]" ] = name.toLowerCase()
  })

  // Define methods that will be available on all
  // Zepto collections
  $.fn = {
    // Because a collection acts like an array
    // copy over these useful array functions.
    forEach: emptyArray.forEach,
    reduce: emptyArray.reduce,
    push: emptyArray.push,
    sort: emptyArray.sort,
    indexOf: emptyArray.indexOf,
    concat: emptyArray.concat,

    // `map` and `slice` in the jQuery API work differently
    // from their array counterparts
    map: function(fn){
      return $($.map(this, function(el, i){ return fn.call(el, i, el) }))
    },
    slice: function(){
      return $(slice.apply(this, arguments))
    },

    ready: function(callback){
      if (readyRE.test(document.readyState)) callback($)
      else document.addEventListener('DOMContentLoaded', function(){ callback($) }, false)
      return this
    },
    get: function(idx){
      return idx === undefined ? slice.call(this) : this[idx >= 0 ? idx : idx + this.length]
    },
    toArray: function(){ return this.get() },
    size: function(){
      return this.length
    },
    remove: function(){
      return this.each(function(){
        if (this.parentNode != null)
          this.parentNode.removeChild(this)
      })
    },
    each: function(callback){
      emptyArray.every.call(this, function(el, idx){
        return callback.call(el, idx, el) !== false
      })
      return this
    },
    filter: function(selector){
      if (isFunction(selector)) return this.not(this.not(selector))
      return $(filter.call(this, function(element){
        return zepto.matches(element, selector)
      }))
    },
    add: function(selector,context){
      return $(uniq(this.concat($(selector,context))))
    },
    is: function(selector){
      return this.length > 0 && zepto.matches(this[0], selector)
    },
    not: function(selector){
      var nodes=[]
      if (isFunction(selector) && selector.call !== undefined)
        this.each(function(idx){
          if (!selector.call(this,idx)) nodes.push(this)
        })
      else {
        var excludes = typeof selector == 'string' ? this.filter(selector) :
          (likeArray(selector) && isFunction(selector.item)) ? slice.call(selector) : $(selector)
        this.forEach(function(el){
          if (excludes.indexOf(el) < 0) nodes.push(el)
        })
      }
      return $(nodes)
    },
    has: function(selector){
      return this.filter(function(){
        return isObject(selector) ?
          $.contains(this, selector) :
          $(this).find(selector).size()
      })
    },
    eq: function(idx){
      return idx === -1 ? this.slice(idx) : this.slice(idx, + idx + 1)
    },
    first: function(){
      var el = this[0]
      return el && !isObject(el) ? el : $(el)
    },
    last: function(){
      var el = this[this.length - 1]
      return el && !isObject(el) ? el : $(el)
    },
    find: function(selector){
      var result, $this = this
      if (typeof selector == 'object')
        result = $(selector).filter(function(){
          var node = this
          return emptyArray.some.call($this, function(parent){
            return $.contains(parent, node)
          })
        })
      else if (this.length == 1) result = $(zepto.qsa(this[0], selector))
      else result = this.map(function(){ return zepto.qsa(this, selector) })
      return result
    },
    closest: function(selector, context){
      var node = this[0], collection = false
      if (typeof selector == 'object') collection = $(selector)
      while (node && !(collection ? collection.indexOf(node) >= 0 : zepto.matches(node, selector)))
        node = node !== context && !isDocument(node) && node.parentNode
      return $(node)
    },
    parents: function(selector){
      var ancestors = [], nodes = this
      while (nodes.length > 0)
        nodes = $.map(nodes, function(node){
          if ((node = node.parentNode) && !isDocument(node) && ancestors.indexOf(node) < 0) {
            ancestors.push(node)
            return node
          }
        })
      return filtered(ancestors, selector)
    },
    parent: function(selector){
      return filtered(uniq(this.pluck('parentNode')), selector)
    },
    children: function(selector){
      return filtered(this.map(function(){ return children(this) }), selector)
    },
    contents: function() {
      return this.map(function() { return slice.call(this.childNodes) })
    },
    siblings: function(selector){
      return filtered(this.map(function(i, el){
        return filter.call(children(el.parentNode), function(child){ return child!==el })
      }), selector)
    },
    empty: function(){
      return this.each(function(){ this.innerHTML = '' })
    },
    // `pluck` is borrowed from Prototype.js
    pluck: function(property){
      return $.map(this, function(el){ return el[property] })
    },
    show: function(){
      return this.each(function(){
        this.style.display == "none" && (this.style.display = null)
        if (getComputedStyle(this, '').getPropertyValue("display") == "none")
          this.style.display = defaultDisplay(this.nodeName)
      })
    },
    replaceWith: function(newContent){
      return this.before(newContent).remove()
    },
    wrap: function(structure){
      var func = isFunction(structure)
      if (this[0] && !func)
        var dom   = $(structure).get(0),
            clone = dom.parentNode || this.length > 1

      return this.each(function(index){
        $(this).wrapAll(
          func ? structure.call(this, index) :
            clone ? dom.cloneNode(true) : dom
        )
      })
    },
    wrapAll: function(structure){
      if (this[0]) {
        $(this[0]).before(structure = $(structure))
        var children
        // drill down to the inmost element
        while ((children = structure.children()).length) structure = children.first()
        $(structure).append(this)
      }
      return this
    },
    wrapInner: function(structure){
      var func = isFunction(structure)
      return this.each(function(index){
        var self = $(this), contents = self.contents(),
            dom  = func ? structure.call(this, index) : structure
        contents.length ? contents.wrapAll(dom) : self.append(dom)
      })
    },
    unwrap: function(){
      this.parent().each(function(){
        $(this).replaceWith($(this).children())
      })
      return this
    },
    clone: function(){
      return this.map(function(){ return this.cloneNode(true) })
    },
    hide: function(){
      return this.css("display", "none")
    },
    toggle: function(setting){
      return this.each(function(){
        var el = $(this)
        ;(setting === undefined ? el.css("display") == "none" : setting) ? el.show() : el.hide()
      })
    },
    prev: function(selector){ return $(this.pluck('previousElementSibling')).filter(selector || '*') },
    next: function(selector){ return $(this.pluck('nextElementSibling')).filter(selector || '*') },
    html: function(html){
      return html === undefined ?
        (this.length > 0 ? this[0].innerHTML : null) :
        this.each(function(idx){
          var originHtml = this.innerHTML
          $(this).empty().append( funcArg(this, html, idx, originHtml) )
        })
    },
    text: function(text){
      return text === undefined ?
        (this.length > 0 ? this[0].textContent : null) :
        this.each(function(){ this.textContent = text })
    },
    attr: function(name, value){
      var result
      return (typeof name == 'string' && value === undefined) ?
        (this.length == 0 || this[0].nodeType !== 1 ? undefined :
          (name == 'value' && this[0].nodeName == 'INPUT') ? this.val() :
          (!(result = this[0].getAttribute(name)) && name in this[0]) ? this[0][name] : result
        ) :
        this.each(function(idx){
          if (this.nodeType !== 1) return
          if (isObject(name)) for (key in name) setAttribute(this, key, name[key])
          else setAttribute(this, name, funcArg(this, value, idx, this.getAttribute(name)))
        })
    },
    removeAttr: function(name){
      return this.each(function(){ this.nodeType === 1 && setAttribute(this, name) })
    },
    prop: function(name, value){
      return (value === undefined) ?
        (this[0] && this[0][name]) :
        this.each(function(idx){
          this[name] = funcArg(this, value, idx, this[name])
        })
    },
    data: function(name, value){
      var data = this.attr('data-' + dasherize(name), value)
      return data !== null ? deserializeValue(data) : undefined
    },
    val: function(value){
      return (value === undefined) ?
        (this[0] && (this[0].multiple ?
           $(this[0]).find('option').filter(function(o){ return this.selected }).pluck('value') :
           this[0].value)
        ) :
        this.each(function(idx){
          this.value = funcArg(this, value, idx, this.value)
        })
    },
    offset: function(coordinates){
      if (coordinates) return this.each(function(index){
        var $this = $(this),
            coords = funcArg(this, coordinates, index, $this.offset()),
            parentOffset = $this.offsetParent().offset(),
            props = {
              top:  coords.top  - parentOffset.top,
              left: coords.left - parentOffset.left
            }

        if ($this.css('position') == 'static') props['position'] = 'relative'
        $this.css(props)
      })
      if (this.length==0) return null
      var obj = this[0].getBoundingClientRect()
      return {
        left: obj.left + window.pageXOffset,
        top: obj.top + window.pageYOffset,
        width: Math.round(obj.width),
        height: Math.round(obj.height)
      }
    },
    css: function(property, value){
      if (arguments.length < 2 && typeof property == 'string')
        return this[0] && (this[0].style[camelize(property)] || getComputedStyle(this[0], '').getPropertyValue(property))

      var css = ''
      if (type(property) == 'string') {
        if (!value && value !== 0)
          this.each(function(){ this.style.removeProperty(dasherize(property)) })
        else
          css = dasherize(property) + ":" + maybeAddPx(property, value)
      } else {
        for (key in property)
          if (!property[key] && property[key] !== 0)
            this.each(function(){ this.style.removeProperty(dasherize(key)) })
          else
            css += dasherize(key) + ':' + maybeAddPx(key, property[key]) + ';'
      }

      return this.each(function(){ this.style.cssText += ';' + css })
    },
    index: function(element){
      return element ? this.indexOf($(element)[0]) : this.parent().children().indexOf(this[0])
    },
    hasClass: function(name){
      return emptyArray.some.call(this, function(el){
        return this.test(className(el))
      }, classRE(name))
    },
    addClass: function(name){
      return this.each(function(idx){
        classList = []
        var cls = className(this), newName = funcArg(this, name, idx, cls)
        newName.split(/\s+/g).forEach(function(klass){
          if (!$(this).hasClass(klass)) classList.push(klass)
        }, this)
        classList.length && className(this, cls + (cls ? " " : "") + classList.join(" "))
      })
    },
    removeClass: function(name){
      return this.each(function(idx){
        if (name === undefined) return className(this, '')
        classList = className(this)
        funcArg(this, name, idx, classList).split(/\s+/g).forEach(function(klass){
          classList = classList.replace(classRE(klass), " ")
        })
        className(this, classList.trim())
      })
    },
    toggleClass: function(name, when){
      return this.each(function(idx){
        var $this = $(this), names = funcArg(this, name, idx, className(this))
        names.split(/\s+/g).forEach(function(klass){
          (when === undefined ? !$this.hasClass(klass) : when) ?
            $this.addClass(klass) : $this.removeClass(klass)
        })
      })
    },
    scrollTop: function(){
      if (!this.length) return
      return ('scrollTop' in this[0]) ? this[0].scrollTop : this[0].scrollY
    },
    position: function() {
      if (!this.length) return

      var elem = this[0],
        // Get *real* offsetParent
        offsetParent = this.offsetParent(),
        // Get correct offsets
        offset       = this.offset(),
        parentOffset = rootNodeRE.test(offsetParent[0].nodeName) ? { top: 0, left: 0 } : offsetParent.offset()

      // Subtract element margins
      // note: when an element has margin: auto the offsetLeft and marginLeft
      // are the same in Safari causing offset.left to incorrectly be 0
      offset.top  -= parseFloat( $(elem).css('margin-top') ) || 0
      offset.left -= parseFloat( $(elem).css('margin-left') ) || 0

      // Add offsetParent borders
      parentOffset.top  += parseFloat( $(offsetParent[0]).css('border-top-width') ) || 0
      parentOffset.left += parseFloat( $(offsetParent[0]).css('border-left-width') ) || 0

      // Subtract the two offsets
      return {
        top:  offset.top  - parentOffset.top,
        left: offset.left - parentOffset.left
      }
    },
    offsetParent: function() {
      return this.map(function(){
        var parent = this.offsetParent || document.body
        while (parent && !rootNodeRE.test(parent.nodeName) && $(parent).css("position") == "static")
          parent = parent.offsetParent
        return parent
      })
    }
  }

  // for now
  $.fn.detach = $.fn.remove

  // Generate the `width` and `height` functions
  ;['width', 'height'].forEach(function(dimension){
    $.fn[dimension] = function(value){
      var offset, el = this[0],
        Dimension = dimension.replace(/./, function(m){ return m[0].toUpperCase() })
      if (value === undefined) return isWindow(el) ? el['inner' + Dimension] :
        isDocument(el) ? el.documentElement['offset' + Dimension] :
        (offset = this.offset()) && offset[dimension]
      else return this.each(function(idx){
        el = $(this)
        el.css(dimension, funcArg(this, value, idx, el[dimension]()))
      })
    }
  })

  function traverseNode(node, fun) {
    fun(node)
    for (var key in node.childNodes) traverseNode(node.childNodes[key], fun)
  }

  // Generate the `after`, `prepend`, `before`, `append`,
  // `insertAfter`, `insertBefore`, `appendTo`, and `prependTo` methods.
  adjacencyOperators.forEach(function(operator, operatorIndex) {
    var inside = operatorIndex % 2 //=> prepend, append

    $.fn[operator] = function(){
      // arguments can be nodes, arrays of nodes, Zepto objects and HTML strings
      var argType, nodes = $.map(arguments, function(arg) {
            argType = type(arg)
            return argType == "object" || argType == "array" || arg == null ?
              arg : zepto.fragment(arg)
          }),
          parent, copyByClone = this.length > 1
      if (nodes.length < 1) return this

      return this.each(function(_, target){
        parent = inside ? target : target.parentNode

        // convert all methods to a "before" operation
        target = operatorIndex == 0 ? target.nextSibling :
                 operatorIndex == 1 ? target.firstChild :
                 operatorIndex == 2 ? target :
                 null

        nodes.forEach(function(node){
          if (copyByClone) node = node.cloneNode(true)
          else if (!parent) return $(node).remove()

          traverseNode(parent.insertBefore(node, target), function(el){
            if (el.nodeName != null && el.nodeName.toUpperCase() === 'SCRIPT' &&
               (!el.type || el.type === 'text/javascript') && !el.src)
              window['eval'].call(window, el.innerHTML)
          })
        })
      })
    }

    // after    => insertAfter
    // prepend  => prependTo
    // before   => insertBefore
    // append   => appendTo
    $.fn[inside ? operator+'To' : 'insert'+(operatorIndex ? 'Before' : 'After')] = function(html){
      $(html)[operator](this)
      return this
    }
  })

  zepto.Z.prototype = $.fn

  // Export internal API functions in the `$.zepto` namespace
  zepto.uniq = uniq
  zepto.deserializeValue = deserializeValue
  $.zepto = zepto

  return $
})()


window.Zepto = Zepto
'$' in window || (window.$ = Zepto)





;(function($){
  function detect(ua){
    var os = this.os = {}, browser = this.browser = {},
      webkit = ua.match(/WebKit\/([\d.]+)/),
      android = ua.match(/(Android)\s+([\d.]+)/),
      ipad = ua.match(/(iPad).*OS\s([\d_]+)/),
      iphone = !ipad && ua.match(/(iPhone\sOS)\s([\d_]+)/),
      webos = ua.match(/(webOS|hpwOS)[\s\/]([\d.]+)/),
      touchpad = webos && ua.match(/TouchPad/),
      kindle = ua.match(/Kindle\/([\d.]+)/),
      silk = ua.match(/Silk\/([\d._]+)/),
      blackberry = ua.match(/(BlackBerry).*Version\/([\d.]+)/),
      bb10 = ua.match(/(BB10).*Version\/([\d.]+)/),
      rimtabletos = ua.match(/(RIM\sTablet\sOS)\s([\d.]+)/),
      playbook = ua.match(/PlayBook/),
      chrome = ua.match(/Chrome\/([\d.]+)/) || ua.match(/CriOS\/([\d.]+)/),
      firefox = ua.match(/Firefox\/([\d.]+)/)

    // Todo: clean this up with a better OS/browser seperation:
    // - discern (more) between multiple browsers on android
    // - decide if kindle fire in silk mode is android or not
    // - Firefox on Android doesn't specify the Android version
    // - possibly devide in os, device and browser hashes

    if (browser.webkit = !!webkit) browser.version = webkit[1]

    if (android) os.android = true, os.version = android[2]
    if (iphone) os.ios = os.iphone = true, os.version = iphone[2].replace(/_/g, '.')
    if (ipad) os.ios = os.ipad = true, os.version = ipad[2].replace(/_/g, '.')
    if (webos) os.webos = true, os.version = webos[2]
    if (touchpad) os.touchpad = true
    if (blackberry) os.blackberry = true, os.version = blackberry[2]
    if (bb10) os.bb10 = true, os.version = bb10[2]
    if (rimtabletos) os.rimtabletos = true, os.version = rimtabletos[2]
    if (playbook) browser.playbook = true
    if (kindle) os.kindle = true, os.version = kindle[1]
    if (silk) browser.silk = true, browser.version = silk[1]
    if (!silk && os.android && ua.match(/Kindle Fire/)) browser.silk = true
    if (chrome) browser.chrome = true, browser.version = chrome[1]
    if (firefox) browser.firefox = true, browser.version = firefox[1]

    os.tablet = !!(ipad || playbook || (android && !ua.match(/Mobile/)) || (firefox && ua.match(/Tablet/)))
    os.phone  = !!(!os.tablet && (android || iphone || webos || blackberry || bb10 ||
      (chrome && ua.match(/Android/)) || (chrome && ua.match(/CriOS\/([\d.]+)/)) || (firefox && ua.match(/Mobile/))))
  }

  detect.call($, navigator.userAgent)
  // make available to unit tests
  $.__detect = detect

})(Zepto)





;(function($){
  var $$ = $.zepto.qsa, handlers = {}, _zid = 1, specialEvents={},
      hover = { mouseenter: 'mouseover', mouseleave: 'mouseout' }

  specialEvents.click = specialEvents.mousedown = specialEvents.mouseup = specialEvents.mousemove = 'MouseEvents'

  function zid(element) {
    return element._zid || (element._zid = _zid++)
  }
  function findHandlers(element, event, fn, selector) {
    event = parse(event)
    if (event.ns) var matcher = matcherFor(event.ns)
    return (handlers[zid(element)] || []).filter(function(handler) {
      return handler
        && (!event.e  || handler.e == event.e)
        && (!event.ns || matcher.test(handler.ns))
        && (!fn       || zid(handler.fn) === zid(fn))
        && (!selector || handler.sel == selector)
    })
  }
  function parse(event) {
    var parts = ('' + event).split('.')
    return {e: parts[0], ns: parts.slice(1).sort().join(' ')}
  }
  function matcherFor(ns) {
    return new RegExp('(?:^| )' + ns.replace(' ', ' .* ?') + '(?: |$)')
  }

  function eachEvent(events, fn, iterator){
    if ($.type(events) != "string") $.each(events, iterator)
    else events.split(/\s/).forEach(function(type){ iterator(type, fn) })
  }

  function eventCapture(handler, captureSetting) {
    return handler.del &&
      (handler.e == 'focus' || handler.e == 'blur') ||
      !!captureSetting
  }

  function realEvent(type) {
    return hover[type] || type
  }

  function add(element, events, fn, selector, getDelegate, capture){
    var id = zid(element), set = (handlers[id] || (handlers[id] = []))
    eachEvent(events, fn, function(event, fn){
      var handler   = parse(event)
      handler.fn    = fn
      handler.sel   = selector
      // emulate mouseenter, mouseleave
      if (handler.e in hover) fn = function(e){
        var related = e.relatedTarget
        if (!related || (related !== this && !$.contains(this, related)))
          return handler.fn.apply(this, arguments)
      }
      handler.del   = getDelegate && getDelegate(fn, event)
      var callback  = handler.del || fn
      handler.proxy = function (e) {
        var result = callback.apply(element, [e].concat(e.data))
        if (result === false) e.preventDefault(), e.stopPropagation()
        return result
      }
      handler.i = set.length
      set.push(handler)
      element.addEventListener(realEvent(handler.e), handler.proxy, eventCapture(handler, capture))
    })
  }
  function remove(element, events, fn, selector, capture){
    var id = zid(element)
    eachEvent(events || '', fn, function(event, fn){
      findHandlers(element, event, fn, selector).forEach(function(handler){
        delete handlers[id][handler.i]
        element.removeEventListener(realEvent(handler.e), handler.proxy, eventCapture(handler, capture))
      })
    })
  }

  $.event = { add: add, remove: remove }

  $.proxy = function(fn, context) {
    if ($.isFunction(fn)) {
      var proxyFn = function(){ return fn.apply(context, arguments) }
      proxyFn._zid = zid(fn)
      return proxyFn
    } else if (typeof context == 'string') {
      return $.proxy(fn[context], fn)
    } else {
      throw new TypeError("expected function")
    }
  }

  $.fn.bind = function(event, callback){
    return this.each(function(){
      add(this, event, callback)
    })
  }
  $.fn.unbind = function(event, callback){
    return this.each(function(){
      remove(this, event, callback)
    })
  }
  $.fn.one = function(event, callback){
    return this.each(function(i, element){
      add(this, event, callback, null, function(fn, type){
        return function(){
          var result = fn.apply(element, arguments)
          remove(element, type, fn)
          return result
        }
      })
    })
  }

  var returnTrue = function(){return true},
      returnFalse = function(){return false},
      ignoreProperties = /^([A-Z]|layer[XY]$)/,
      eventMethods = {
        preventDefault: 'isDefaultPrevented',
        stopImmediatePropagation: 'isImmediatePropagationStopped',
        stopPropagation: 'isPropagationStopped'
      }
  function createProxy(event) {
    var key, proxy = { originalEvent: event }
    for (key in event)
      if (!ignoreProperties.test(key) && event[key] !== undefined) proxy[key] = event[key]

    $.each(eventMethods, function(name, predicate) {
      proxy[name] = function(){
        this[predicate] = returnTrue
        return event[name].apply(event, arguments)
      }
      proxy[predicate] = returnFalse
    })
    return proxy
  }

  // emulates the 'defaultPrevented' property for browsers that have none
  function fix(event) {
    if (!('defaultPrevented' in event)) {
      event.defaultPrevented = false
      var prevent = event.preventDefault
      event.preventDefault = function() {
        this.defaultPrevented = true
        prevent.call(this)
      }
    }
  }

  $.fn.delegate = function(selector, event, callback){
    return this.each(function(i, element){
      add(element, event, callback, selector, function(fn){
        return function(e){
          var evt, match = $(e.target).closest(selector, element).get(0)
          if (match) {
            evt = $.extend(createProxy(e), {currentTarget: match, liveFired: element})
            return fn.apply(match, [evt].concat([].slice.call(arguments, 1)))
          }
        }
      })
    })
  }
  $.fn.undelegate = function(selector, event, callback){
    return this.each(function(){
      remove(this, event, callback, selector)
    })
  }

  $.fn.live = function(event, callback){
    $(document.body).delegate(this.selector, event, callback)
    return this
  }
  $.fn.die = function(event, callback){
    $(document.body).undelegate(this.selector, event, callback)
    return this
  }

  $.fn.on = function(event, selector, callback){
    return !selector || $.isFunction(selector) ?
      this.bind(event, selector || callback) : this.delegate(selector, event, callback)
  }
  $.fn.off = function(event, selector, callback){
    return !selector || $.isFunction(selector) ?
      this.unbind(event, selector || callback) : this.undelegate(selector, event, callback)
  }

  $.fn.trigger = function(event, data){
    if (typeof event == 'string' || $.isPlainObject(event)) event = $.Event(event)
    fix(event)
    event.data = data
    return this.each(function(){
      // items in the collection might not be DOM elements
      // (todo: possibly support events on plain old objects)
      if('dispatchEvent' in this) this.dispatchEvent(event)
    })
  }

  // triggers event handlers on current element just as if an event occurred,
  // doesn't trigger an actual event, doesn't bubble
  $.fn.triggerHandler = function(event, data){
    var e, result
    this.each(function(i, element){
      e = createProxy(typeof event == 'string' ? $.Event(event) : event)
      e.data = data
      e.target = element
      $.each(findHandlers(element, event.type || event), function(i, handler){
        result = handler.proxy(e)
        if (e.isImmediatePropagationStopped()) return false
      })
    })
    return result
  }

  // shortcut methods for `.bind(event, fn)` for each event type
  ;('focusin focusout load resize scroll unload click dblclick '+
  'mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave '+
  'change select keydown keypress keyup error').split(' ').forEach(function(event) {
    $.fn[event] = function(callback) {
      return callback ?
        this.bind(event, callback) :
        this.trigger(event)
    }
  })

  ;['focus', 'blur'].forEach(function(name) {
    $.fn[name] = function(callback) {
      if (callback) this.bind(name, callback)
      else this.each(function(){
        try { this[name]() }
        catch(e) {}
      })
      return this
    }
  })

  $.Event = function(type, props) {
    if (typeof type != 'string') props = type, type = props.type
    var event = document.createEvent(specialEvents[type] || 'Events'), bubbles = true
    if (props) for (var name in props) (name == 'bubbles') ? (bubbles = !!props[name]) : (event[name] = props[name])
    event.initEvent(type, bubbles, true, null, null, null, null, null, null, null, null, null, null, null, null)
    event.isDefaultPrevented = function(){ return this.defaultPrevented }
    return event
  }

})(Zepto)





;(function($){
  var jsonpID = 0,
      document = window.document,
      key,
      name,
      rscript = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
      scriptTypeRE = /^(?:text|application)\/javascript/i,
      xmlTypeRE = /^(?:text|application)\/xml/i,
      jsonType = 'application/json',
      htmlType = 'text/html',
      blankRE = /^\s*$/

  // trigger a custom event and return false if it was cancelled
  function triggerAndReturn(context, eventName, data) {
    var event = $.Event(eventName)
    $(context).trigger(event, data)
    return !event.defaultPrevented
  }

  // trigger an Ajax "global" event
  function triggerGlobal(settings, context, eventName, data) {
    if (settings.global) return triggerAndReturn(context || document, eventName, data)
  }

  // Number of active Ajax requests
  $.active = 0

  function ajaxStart(settings) {
    if (settings.global && $.active++ === 0) triggerGlobal(settings, null, 'ajaxStart')
  }
  function ajaxStop(settings) {
    if (settings.global && !(--$.active)) triggerGlobal(settings, null, 'ajaxStop')
  }

  // triggers an extra global event "ajaxBeforeSend" that's like "ajaxSend" but cancelable
  function ajaxBeforeSend(xhr, settings) {
    var context = settings.context
    if (settings.beforeSend.call(context, xhr, settings) === false ||
        triggerGlobal(settings, context, 'ajaxBeforeSend', [xhr, settings]) === false)
      return false

    triggerGlobal(settings, context, 'ajaxSend', [xhr, settings])
  }
  function ajaxSuccess(data, xhr, settings) {
    var context = settings.context, status = 'success'
    settings.success.call(context, data, status, xhr)
    triggerGlobal(settings, context, 'ajaxSuccess', [xhr, settings, data])
    ajaxComplete(status, xhr, settings)
  }
  // type: "timeout", "error", "abort", "parsererror"
  function ajaxError(error, type, xhr, settings) {
    var context = settings.context
    settings.error.call(context, xhr, type, error)
    triggerGlobal(settings, context, 'ajaxError', [xhr, settings, error])
    ajaxComplete(type, xhr, settings)
  }
  // status: "success", "notmodified", "error", "timeout", "abort", "parsererror"
  function ajaxComplete(status, xhr, settings) {
    var context = settings.context
    settings.complete.call(context, xhr, status)
    triggerGlobal(settings, context, 'ajaxComplete', [xhr, settings])
    ajaxStop(settings)
  }

  // Empty function, used as default callback
  function empty() {}

  $.ajaxJSONP = function(options){
    if (!('type' in options)) return $.ajax(options)

    var callbackName = 'jsonp' + (++jsonpID),
      script = document.createElement('script'),
      cleanup = function() {
        clearTimeout(abortTimeout)
        $(script).remove()
        delete window[callbackName]
      },
      abort = function(type){
        cleanup()
        // In case of manual abort or timeout, keep an empty function as callback
        // so that the SCRIPT tag that eventually loads won't result in an error.
        if (!type || type == 'timeout') window[callbackName] = empty
        ajaxError(null, type || 'abort', xhr, options)
      },
      xhr = { abort: abort }, abortTimeout

    if (ajaxBeforeSend(xhr, options) === false) {
      abort('abort')
      return false
    }

    window[callbackName] = function(data){
      cleanup()
      ajaxSuccess(data, xhr, options)
    }

    script.onerror = function() { abort('error') }

    script.src = options.url.replace(/=\?/, '=' + callbackName)
    $('head').append(script)

    if (options.timeout > 0) abortTimeout = setTimeout(function(){
      abort('timeout')
    }, options.timeout)

    return xhr
  }

  $.ajaxSettings = {
    // Default type of request
    type: 'GET',
    // Callback that is executed before request
    beforeSend: empty,
    // Callback that is executed if the request succeeds
    success: empty,
    // Callback that is executed the the server drops error
    error: empty,
    // Callback that is executed on request complete (both: error and success)
    complete: empty,
    // The context for the callbacks
    context: null,
    // Whether to trigger "global" Ajax events
    global: true,
    // Transport
    xhr: function () {
      return new window.XMLHttpRequest()
    },
    // MIME types mapping
    accepts: {
      script: 'text/javascript, application/javascript',
      json:   jsonType,
      xml:    'application/xml, text/xml',
      html:   htmlType,
      text:   'text/plain'
    },
    // Whether the request is to another domain
    crossDomain: false,
    // Default timeout
    timeout: 0,
    // Whether data should be serialized to string
    processData: true,
    // Whether the browser should be allowed to cache GET responses
    cache: true
  }

  function mimeToDataType(mime) {
    if (mime) mime = mime.split(';', 2)[0]
    return mime && ( mime == htmlType ? 'html' :
      mime == jsonType ? 'json' :
      scriptTypeRE.test(mime) ? 'script' :
      xmlTypeRE.test(mime) && 'xml' ) || 'text'
  }

  function appendQuery(url, query) {
    return (url + '&' + query).replace(/[&?]{1,2}/, '?')
  }

  // serialize payload and append it to the URL for GET requests
  function serializeData(options) {
    if (options.processData && options.data && $.type(options.data) != "string")
      options.data = $.param(options.data, options.traditional)
    if (options.data && (!options.type || options.type.toUpperCase() == 'GET'))
      options.url = appendQuery(options.url, options.data)
  }

  $.ajax = function(options){
    var settings = $.extend({}, options || {})
    for (key in $.ajaxSettings) if (settings[key] === undefined) settings[key] = $.ajaxSettings[key]

    ajaxStart(settings)

    if (!settings.crossDomain) settings.crossDomain = /^([\w-]+:)?\/\/([^\/]+)/.test(settings.url) &&
      RegExp.$2 != window.location.host

    if (!settings.url) settings.url = window.location.toString()
    serializeData(settings)
    if (settings.cache === false) settings.url = appendQuery(settings.url, '_=' + Date.now())

    var dataType = settings.dataType, hasPlaceholder = /=\?/.test(settings.url)
    if (dataType == 'jsonp' || hasPlaceholder) {
      if (!hasPlaceholder) settings.url = appendQuery(settings.url, 'callback=?')
      return $.ajaxJSONP(settings)
    }

    var mime = settings.accepts[dataType],
        baseHeaders = { },
        protocol = /^([\w-]+:)\/\//.test(settings.url) ? RegExp.$1 : window.location.protocol,
        xhr = settings.xhr(), abortTimeout

    if (!settings.crossDomain) baseHeaders['X-Requested-With'] = 'XMLHttpRequest'
    if (mime) {
      baseHeaders['Accept'] = mime
      if (mime.indexOf(',') > -1) mime = mime.split(',', 2)[0]
      xhr.overrideMimeType && xhr.overrideMimeType(mime)
    }
    if (settings.contentType || (settings.contentType !== false && settings.data && settings.type.toUpperCase() != 'GET'))
      baseHeaders['Content-Type'] = (settings.contentType || 'application/x-www-form-urlencoded')
    settings.headers = $.extend(baseHeaders, settings.headers || {})

    xhr.onreadystatechange = function(){
      if (xhr.readyState == 4) {
        xhr.onreadystatechange = empty;
        clearTimeout(abortTimeout)
        var result, error = false
        if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304 || (xhr.status == 0 && protocol == 'file:')) {
          dataType = dataType || mimeToDataType(xhr.getResponseHeader('content-type'))
          result = xhr.responseText

          try {
            // http://perfectionkills.com/global-eval-what-are-the-options/
            if (dataType == 'script')    (1,eval)(result)
            else if (dataType == 'xml')  result = xhr.responseXML
            else if (dataType == 'json') result = blankRE.test(result) ? null : $.parseJSON(result)
          } catch (e) { error = e }

          if (error) ajaxError(error, 'parsererror', xhr, settings)
          else ajaxSuccess(result, xhr, settings)
        } else {
          ajaxError(null, xhr.status ? 'error' : 'abort', xhr, settings)
        }
      }
    }

    var async = 'async' in settings ? settings.async : true
    xhr.open(settings.type, settings.url, async)

    for (name in settings.headers) xhr.setRequestHeader(name, settings.headers[name])

    if (ajaxBeforeSend(xhr, settings) === false) {
      xhr.abort()
      return false
    }

    if (settings.timeout > 0) abortTimeout = setTimeout(function(){
        xhr.onreadystatechange = empty
        xhr.abort()
        ajaxError(null, 'timeout', xhr, settings)
      }, settings.timeout)

    // avoid sending empty string (#319)
    xhr.send(settings.data ? settings.data : null)
    return xhr
  }

  // handle optional data/success arguments
  function parseArguments(url, data, success, dataType) {
    var hasData = !$.isFunction(data)
    return {
      url:      url,
      data:     hasData  ? data : undefined,
      success:  !hasData ? data : $.isFunction(success) ? success : undefined,
      dataType: hasData  ? dataType || success : success
    }
  }

  $.get = function(url, data, success, dataType){
    return $.ajax(parseArguments.apply(null, arguments))
  }

  $.post = function(url, data, success, dataType){
    var options = parseArguments.apply(null, arguments)
    options.type = 'POST'
    return $.ajax(options)
  }

  $.getJSON = function(url, data, success){
    var options = parseArguments.apply(null, arguments)
    options.dataType = 'json'
    return $.ajax(options)
  }

  $.fn.load = function(url, data, success){
    if (!this.length) return this
    var self = this, parts = url.split(/\s/), selector,
        options = parseArguments(url, data, success),
        callback = options.success
    if (parts.length > 1) options.url = parts[0], selector = parts[1]
    options.success = function(response){
      self.html(selector ?
        $('<div>').html(response.replace(rscript, "")).find(selector)
        : response)
      callback && callback.apply(self, arguments)
    }
    $.ajax(options)
    return this
  }

  var escape = encodeURIComponent

  function serialize(params, obj, traditional, scope){
    var type, array = $.isArray(obj)
    $.each(obj, function(key, value) {
      type = $.type(value)
      if (scope) key = traditional ? scope : scope + '[' + (array ? '' : key) + ']'
      // handle data in serializeArray() format
      if (!scope && array) params.add(value.name, value.value)
      // recurse into nested objects
      else if (type == "array" || (!traditional && type == "object"))
        serialize(params, value, traditional, key)
      else params.add(key, value)
    })
  }

  $.param = function(obj, traditional){
    var params = []
    params.add = function(k, v){ this.push(escape(k) + '=' + escape(v)) }
    serialize(params, obj, traditional)
    return params.join('&').replace(/%20/g, '+')
  }
})(Zepto)





;(function ($) {
  $.fn.serializeArray = function () {
    var result = [], el
    $( Array.prototype.slice.call(this.get(0).elements) ).each(function () {
      el = $(this)
      var type = el.attr('type')
      if (this.nodeName.toLowerCase() != 'fieldset' &&
        !this.disabled && type != 'submit' && type != 'reset' && type != 'button' &&
        ((type != 'radio' && type != 'checkbox') || this.checked))
        result.push({
          name: el.attr('name'),
          value: el.val()
        })
    })
    return result
  }

  $.fn.serialize = function () {
    var result = []
    this.serializeArray().forEach(function (elm) {
      result.push( encodeURIComponent(elm.name) + '=' + encodeURIComponent(elm.value) )
    })
    return result.join('&')
  }

  $.fn.submit = function (callback) {
    if (callback) this.bind('submit', callback)
    else if (this.length) {
      var event = $.Event('submit')
      this.eq(0).trigger(event)
      if (!event.defaultPrevented) this.get(0).submit()
    }
    return this
  }

})(Zepto)





;(function($, undefined){
  var prefix = '', eventPrefix, endEventName, endAnimationName,
    vendors = { Webkit: 'webkit', Moz: '', O: 'o', ms: 'MS' },
    document = window.document, testEl = document.createElement('div'),
    supportedTransforms = /^((translate|rotate|scale)(X|Y|Z|3d)?|matrix(3d)?|perspective|skew(X|Y)?)$/i,
    transform,
    transitionProperty, transitionDuration, transitionTiming,
    animationName, animationDuration, animationTiming,
    cssReset = {}

  function dasherize(str) { return downcase(str.replace(/([a-z])([A-Z])/, '$1-$2')) }
  function downcase(str) { return str.toLowerCase() }
  function normalizeEvent(name) { return eventPrefix ? eventPrefix + name : downcase(name) }

  $.each(vendors, function(vendor, event){
    if (testEl.style[vendor + 'TransitionProperty'] !== undefined) {
      prefix = '-' + downcase(vendor) + '-'
      eventPrefix = event
      return false
    }
  })

  transform = prefix + 'transform'
  cssReset[transitionProperty = prefix + 'transition-property'] =
  cssReset[transitionDuration = prefix + 'transition-duration'] =
  cssReset[transitionTiming   = prefix + 'transition-timing-function'] =
  cssReset[animationName      = prefix + 'animation-name'] =
  cssReset[animationDuration  = prefix + 'animation-duration'] =
  cssReset[animationTiming    = prefix + 'animation-timing-function'] = ''

  $.fx = {
    off: (eventPrefix === undefined && testEl.style.transitionProperty === undefined),
    speeds: { _default: 400, fast: 200, slow: 600 },
    cssPrefix: prefix,
    transitionEnd: normalizeEvent('TransitionEnd'),
    animationEnd: normalizeEvent('AnimationEnd')
  }

  $.fn.animate = function(properties, duration, ease, callback){
    if ($.isPlainObject(duration))
      ease = duration.easing, callback = duration.complete, duration = duration.duration
    if (duration) duration = (typeof duration == 'number' ? duration :
                    ($.fx.speeds[duration] || $.fx.speeds._default)) / 1000
    return this.anim(properties, duration, ease, callback)
  }

  $.fn.anim = function(properties, duration, ease, callback){
    var key, cssValues = {}, cssProperties, transforms = '',
        that = this, wrappedCallback, endEvent = $.fx.transitionEnd

    if (duration === undefined) duration = 0.4
    if ($.fx.off) duration = 0

    if (typeof properties == 'string') {
      // keyframe animation
      cssValues[animationName] = properties
      cssValues[animationDuration] = duration + 's'
      cssValues[animationTiming] = (ease || 'linear')
      endEvent = $.fx.animationEnd
    } else {
      cssProperties = []
      // CSS transitions
      for (key in properties)
        if (supportedTransforms.test(key)) transforms += key + '(' + properties[key] + ') '
        else cssValues[key] = properties[key], cssProperties.push(dasherize(key))

      if (transforms) cssValues[transform] = transforms, cssProperties.push(transform)
      if (duration > 0 && typeof properties === 'object') {
        cssValues[transitionProperty] = cssProperties.join(', ')
        cssValues[transitionDuration] = duration + 's'
        cssValues[transitionTiming] = (ease || 'linear')
      }
    }

    wrappedCallback = function(event){
      if (typeof event !== 'undefined') {
        if (event.target !== event.currentTarget) return // makes sure the event didn't bubble from "below"
        $(event.target).unbind(endEvent, wrappedCallback)
      }
      $(this).css(cssReset)
      callback && callback.call(this)
    }
    if (duration > 0) this.bind(endEvent, wrappedCallback)

    // trigger page reflow so new elements can animate
    this.size() && this.get(0).clientLeft

    this.css(cssValues)

    if (duration <= 0) setTimeout(function() {
      that.each(function(){ wrappedCallback.call(this) })
    }, 0)

    return this
  }

  testEl = null
})(Zepto)
//     Zepto.js
//     (c) 2010-2012 Thomas Fuchs
//     Zepto.js may be freely distributed under the MIT license.

;(function($){
    var touch = {},
        touchTimeout, tapTimeout, swipeTimeout,
        longTapDelay = 750, longTapTimeout

    function parentIfText(node) {
        return 'tagName' in node ? node : node.parentNode
    }

    function swipeDirection(x1, x2, y1, y2) {
        var xDelta = Math.abs(x1 - x2), yDelta = Math.abs(y1 - y2)
        return xDelta >= yDelta ? (x1 - x2 > 0 ? 'Left' : 'Right') : (y1 - y2 > 0 ? 'Up' : 'Down')
    }

    function longTap() {
        longTapTimeout = null
        if (touch.last) {
            touch.el.trigger('longTap')
            touch = {}
        }
    }

    function cancelLongTap() {
        if (longTapTimeout) clearTimeout(longTapTimeout)
        longTapTimeout = null
    }

    function cancelAll() {
        if (touchTimeout) clearTimeout(touchTimeout)
        if (tapTimeout) clearTimeout(tapTimeout)
        if (swipeTimeout) clearTimeout(swipeTimeout)
        if (longTapTimeout) clearTimeout(longTapTimeout)
        touchTimeout = tapTimeout = swipeTimeout = longTapTimeout = null
        touch = {}
    }

    $(document).ready(function(){
        var now, delta

        $(document.body)
            .bind('touchstart', function(e){
                now = Date.now()
                delta = now - (touch.last || now)
                touch.el = $(parentIfText(e.touches[0].target))
                touchTimeout && clearTimeout(touchTimeout)
                touch.x1 = e.touches[0].pageX
                touch.y1 = e.touches[0].pageY
                if (delta > 0 && delta <= 250) touch.isDoubleTap = true
                touch.last = now
                longTapTimeout = setTimeout(longTap, longTapDelay)
            })
            .bind('touchmove', function(e){
                cancelLongTap()
                touch.x2 = e.touches[0].pageX
                touch.y2 = e.touches[0].pageY
                if (Math.abs(touch.x1 - touch.x2) > 10)
                    e.preventDefault()
            })
            .bind('touchend', function(e){
                cancelLongTap()

                // swipe
                if ((touch.x2 && Math.abs(touch.x1 - touch.x2) > 30) ||
                    (touch.y2 && Math.abs(touch.y1 - touch.y2) > 30))

                    swipeTimeout = setTimeout(function() {
                        touch.el.trigger('swipe')
                        touch.el.trigger('swipe' + (swipeDirection(touch.x1, touch.x2, touch.y1, touch.y2)))
                        touch = {}
                    }, 0)

                // normal tap
                else if ('last' in touch)

                // delay by one tick so we can cancel the 'tap' event if 'scroll' fires
                // ('tap' fires before 'scroll')
                    tapTimeout = setTimeout(function() {

                        // trigger universal 'tap' with the option to cancelTouch()
                        // (cancelTouch cancels processing of single vs double taps for faster 'tap' response)
                        var event = $.Event('tap')
                        event.cancelTouch = cancelAll
                        touch.el.trigger(event)

                        // trigger double tap immediately
                        if (touch.isDoubleTap) {
                            touch.el.trigger('doubleTap')
                            touch = {}
                        }

                        // trigger single tap after 250ms of inactivity
                        else {
                            touchTimeout = setTimeout(function(){
                                touchTimeout = null
                                touch.el.trigger('singleTap')
                                touch = {}
                            }, 250)
                        }

                    }, 0)

            })
            .bind('touchcancel', cancelAll)

        $(window).bind('scroll', cancelAll)
    })

    ;['swipe', 'swipeLeft', 'swipeRight', 'swipeUp', 'swipeDown', 'doubleTap', 'tap', 'singleTap', 'longTap'].forEach(function(m){
        $.fn[m] = function(callback){ return this.bind(m, callback) }
    })
})(Zepto)

;/*!/js/module/gj.alias.js*/
(function($){
    /*
     * alias func
     * 简化一些常用方法的写法
     ** /
    /**
     * 完善zepto的动画函数,让参数变为可选
     */
    J.anim  =  function(el,animName,duration,ease,callback){
        var d, e,c;
        var len = arguments.length;
        for(var i = 2;i<len;i++){
            var a = arguments[i];
            var t = $.type(a);
            t == 'number'?(d=a):(t=='string'?(e=a):(t=='function')?(c=a):null);
        }
        $(el).animate(animName,d|| J.settings.transitionTime,e||J.settings.transitionTimingFunc,c);
    }
    /**
     * 显示loading框
     * @param text
     */
    J.showMask = function(text){
        J.Popup.loading(text);
    }
    /**
     * 关闭loading框
     */
    J.hideMask = function(){
        J.Popup.close(true);
    }
    /**
     *  显示消息
     * @param text
     * @param type toast|success|error|info
     * @param duration 持续时间，为0则不自动关闭
     */
    J.showToast = function(text,type,duration){
        type = type || 'toast';
        J.Toast.show(type,text,duration);
    }
    /**
     * 关闭消息提示
     */
    J.hideToast = function(){
        J.Toast.hide();
    }
    J.alert = function(title,content){
        J.Popup.alert(title,content);
    }
    J.confirm = function(title,content,okCall,cancelCall){
        J.Popup.confirm(title,content,okCall,cancelCall);
    }
    /**
     * 弹出窗口
     * @param options
     */
    J.popup = function(options){
        J.Popup.show(options);
    }
    /**
     * 关闭窗口
     */
    J.closePopup = function(){
        J.Popup.close();
    }
    /**
     * 带箭头的弹出框
     * @param html [可选]
     * @param pos [可选]  位置
     * @param arrowDirection [可选] 箭头方向
     * @param onShow [可选] 显示之前执行
     */
    J.popover = function(html,pos,arrowDirection,onShow){
        J.Popup.popover(html,pos,arrowDirection,onShow);
    }
    /**
     * 自动渲染模板并填充到页面
     * @param containerSelector 欲填充的容器
     * @param templateId 模板ID
     * @param data 数据源
     * @param type [可选] add|replace
     * no time to set so delete this
     */
    //J.tmpl = function(containerSelector,templateId,data,type){
    //    J.Template.render(containerSelector,templateId,data,type);
    //}
})(J.$);
;/*!/js/module/gj.Page.js*/
/**
 * section 页面远程加载
 */
J.Page = (function($){
    var _formatHash = function(hash){
        return hash.indexOf('#') == 0 ? hash.substr(1) : hash;
    }
    /**
     * 加载section模板
     * @param {string} hash信息
     * @param {string} url参数
     */
    var loadSectionTpl = function(hash,callback){
        var param = {},query,replaceSection = false;
        if($.type(hash) == 'object'){
            param = hash.param;
            query = hash.query;
            hash = hash.tag;
        }
        var q = $(hash).data('query');
        //已经存在则直接跳转到对应的页面
        if($(hash).length == 1){
            if(q == query){
                callback();
                return;
            }else{
                replaceSection = true;
            }
        }
        var id = _formatHash(hash);
        //当前dom中不存在，需要从服务端加载
        var url = J.settings.remotePage[hash];
        //检查remotePage中是否有配置,没有则自动从basePagePath中装载模板
        url || (url = J.settings.basePagePath+id+J.settings.basePageSuffix);
        J.settings.showPageLoading && J.showMask();
        loadContent(url,param,function(html){
            J.settings.showPageLoading && J.hideMask();
            //添加到dom树中
            $(hash).remove();
            var $h = $(html);
            $('#section_container').append($h);
            if(replaceSection){
                $h.addClass('active');
            }
            //触发pageload事件
            $h.trigger('pageload').data('query',query);
            //构造组件
            J.Element.init(hash);
            callback();
            $h = null;
        });
    }
    var loadSectionRemote = function(url,section){
        var param = J.Util.parseHash(window.location.hash).param;
        loadContent(url,param,function(html){
            $(section).html(html);
            J.Element.init(section);
        });
    }
    /**
     * 加载文档片段
     * @param url
     */
    var loadContent = function(url,param,callback){
        if(J.Util.test())
        {
            var url = window.location.origin+"/html5/shucai/"+url;
        }
        return $.ajax({
                url : url,
                timeout : 20000,
                data : param,
                success : function(html){
                    callback && callback(html);
                }
            });
    }
    return {
        load : loadSectionTpl,
        loadSection : loadSectionRemote,
        loadContent : loadContent
    }
})(J.$);
;/*!/js/module/gj.Popup.js*/
/**
 * 弹出框组件
 */
J.Popup = (function($){
    var _popup,_mask,transition,clickMask2close,fixedPage,
        POSITION = {
            'top':{
                top:0,
                left:0,
                right:0
            },
            'top-second':{
                top:'44px',
                left:0,
                right:0
            },
            'center':{
                top:'50%',
                left:'5%',
                right:'5%',
                'border-radius' : '3px',
            },
            'bottom' : {
                bottom:0,
                left:0,
                right:0
            },
            'bottom-second':{
                bottom : '51px',
                left:0,
                right:0
            }
        },
        ANIM = {
            top : ['slideDownIn','slideUpOut'],
            bottom : ['slideUpIn','slideDownOut'],
            defaultAnim : ['bounceIn','bounceOut'] 
        },
        TEMPLATE = {
            alert : '<div class="popup-title">{title}</div><div class="popup-content">{content}</div><div id="popup_btn_container"><a data-target="closePopup" data-icon="checkmark">{ok}</a></div>',
            confirm : '<div class="popup-title">{title}</div><div class="popup-content">{content}</div><div id="popup_btn_container"><a class="cancel" data-icon="close">{cancel}</a><a data-icon="checkmark">{ok}</a></div>',
            confirma : '<div class="popup-content tc">{content}</div><div id="popup_btn_container"><a class="cancel" data-icon="close">{cancel}</a><a data-icon="checkmark">{ok}</a></div>',
            loading : '<i class="icon spinner"></i><p>{title}</p>'
        };

    /**
     * 全局只有一个popup实例
     * @private
     */
    var _init = function(){
        $('body').append('<div id="gj_popup"></div><div id="gj_popup_mask"></div>');
        _mask = $('#gj_popup_mask');
        _popup = $('#gj_popup');
        _subscribeEvents();
    }

    var show = function(options){
        var settings = {
            height : undefined,//高度
            width : undefined,//宽度
            opacity : 0.8,//透明度
            url : null,//远程加载url
            tplId : null,//加载模板ID
            tplData : null,//模板数据，配合tplId使用
            html : '',//popup内容
            pos : 'center',//位置 {@String top|top-second|center|bottom|bottom-second}   {@object  css样式}
            clickMask2Close : true,// 是否点击外层遮罩关闭popup
            showCloseBtn : true,// 是否显示关闭按钮
			fixedPage: false, //是否固定页面
            arrowDirection : undefined,//popover的箭头指向
            animation : true,//是否显示动画
            timingFunc : 'linear',
            duration : 200,//动画执行时间
            onShow : undefined //@event 在popup内容加载完毕，动画开始前触发
        }
        $.extend(settings,options);
        clickMask2close = settings.clickMask2Close;
        _mask.css('opacity',settings.opacity);
        //rest position and class
        _popup.attr({'style':'','class':''});
        settings.width && _popup.width(settings.width);
        settings.height && _popup.height(settings.height);
		settings.fixedPage && $("html, body").css("overflow", "hidden");
		fixedPage = settings.fixedPage;
        var pos_type = $.type(settings.pos);
        if(pos_type == 'object'){// style
            _popup.css(settings.pos);
            transition = ANIM['defaultAnim'];
        }else if(pos_type == 'string'){
            if(POSITION[settings.pos]){ //已经默认的样式
                _popup.css(POSITION[settings.pos])
                var trans_key = settings.pos.indexOf('top')>-1?'top':(settings.pos.indexOf('bottom')>-1?'bottom':'defaultAnim');
                transition = ANIM[trans_key];
            }else{// pos 为 class
                _popup.addClass(settings.pos);
                transition = ANIM['defaultAnim'];
            }
        }else{
            console.error('错误的参数！');
            return;
        }
        _mask.show();
        var html;
        if(settings.html){
            html = settings.html;
        }else if(settings.url){//远程加载
            html = J.Page.loadContent(settings.url);
        }else if(settings.tplId){//加载模板
            html = template(settings.tplId,settings.tplData)
        }

        //是否显示关闭按钮
        if(settings.showCloseBtn){
            html += '<div id="tag_close_popup" data-target="closePopup" class="icon cancel-circle"></div>';
        }
        //popover 箭头方向
        if(settings.arrowDirection){
            _popup.addClass('arrow '+settings.arrowDirection);
            _popup.css('padding','8px');
            if(settings.arrowDirection=='top'||settings.arrowDirection=='bottom'){
                transition = ANIM[settings.arrowDirection];
            }
        }

        _popup.html(html).show();
      //  J.Element.init(_popup);
        //执行onShow事件，可以动态添加内容
        settings.onShow && settings.onShow.call(_popup);

        //显示获取容器高度，调整至垂直居中
        if(settings.pos == 'center'){
            var height = _popup.height();
            _popup.css('margin-top','-'+height/2+'px')
        }
        if(settings.animation){
            J.anim(_popup,transition[0],settings.duration,settings.timingFunc);
        }
        J.hasPopupOpen = true;
    }

    /**
     * 关闭弹出框
     * @param noTransition 立即关闭，无动画
     */
    var hide = function(noTransition){
        _mask.hide();
        if(transition && !noTransition){
            J.anim(_popup,transition[1],200,function(){
                _popup.hide().empty();
                J.hasPopupOpen = false;
            });
        }else{
            _popup.hide().empty();
            J.hasPopupOpen = false;
        }
		fixedPage && $("html, body").css("overflow", "visible");
    }
    var _subscribeEvents = function(){
        _mask.on('tap',function(){
            clickMask2close &&  hide();
        });
        _popup.on('tap','[data-target="closePopup"]',function(){hide();});
    }

    /**
     * alert组件
     * @param title 标题
     * @param content 内容
     */
    var alert = function(title,content,btnName){
        var markup = TEMPLATE.alert.replace('{title}',title).replace('{content}',content).replace('{ok}',btnName || '确定');
        show({
            html : markup,
            pos : 'center',
            clickMask2Close : false,
            showCloseBtn : false
        });
    }

    /**
     * confirm 组件
     * @param title 标题
     * @param content 内容
     * @param okCall 确定按钮handler
     * @param cancelCall 取消按钮handler
     */
    var confirm = function(title,content,okCall,cancelCall){
        var markup
        if(title)
        {
            markup = TEMPLATE.confirm.replace('{title}',title).replace('{content}',content).replace('{cancel}','取消').replace('{ok}','确定');
        }
        else
        {
            markup = TEMPLATE.confirma.replace('{content}',content).replace('{cancel}','取消').replace('{ok}','确定');
        }
        show({
            html : markup,
            pos : 'center',
            clickMask2Close : false,
            showCloseBtn : false
        });
        $('#popup_btn_container [data-icon="checkmark"]').on("click",function(){
            hide();
            if(okCall) okCall.call(this);
        });
        $('#popup_btn_container [data-icon="close"]').on("click",function(){
            hide();
            if(cancelCall) cancelCall.call(this);
        });
    }

    /**
     * 带箭头的弹出框
     * @param html 弹出框内容
     * @param pos 位置
     * @param arrow_direction 箭头方向
     * @param onShow onShow事件
     */
    var popover = function(html,pos,arrow_direction,onShow){
        show({
            html : html,
            pos : pos,
            showCloseBtn : false,
            arrowDirection : arrow_direction,
            onShow : onShow
        });
    }

    /**
     * loading组件
     * @param text 文本，默认为“加载中...”
     */
    var loading = function(text){
        var markup = TEMPLATE.loading.replace('{title}',text||'加载中...');
        show({
            html : markup,
            pos : 'loading',
            opacity :.1,
            animation : true,
            clickMask2Close : false
        });
    }

    /**
     * actionsheet组件
     * @param buttons 按钮集合
     * [{color:'red',text:'btn',handler:function(){}},{color:'red',text:'btn',handler:function(){}}]
     */
    var actionsheet = function(buttons){
        var markup = '<div class="actionsheet">';
        $.each(buttons,function(i,n){
            markup += '<button style="background-color: '+ n.color +' !important;">'+ n.text +'</button>';
        });
        markup += '<button class="alizarin">取消</button>';
        markup += '</div>';
        show({
            html : markup,
            pos : 'bottom',
            showCloseBtn : false,
            onShow : function(){
                $(this).find('button').each(function(i,button){
                    $(button).on('tap',function(){
                        if(buttons[i] && buttons[i].handler){
                            buttons[i].handler.call(button);
                        }
                        hide();
                    });
                });
            }
        });
    }

    _init();

    return {
        show : show,
        close : hide,
        alert : alert,
        confirm : confirm,
        popover : popover,
        loading : loading,
        actionsheet : actionsheet
    }
})(J.$);
;/*!/js/module/gj.Selected.js*/
/**
 * 高亮组件
 * 在zepto的tap事件里注入了一个延时器，来实现点击态
 */
J.Selected = (function($){
    var DELAY = 100,SELECTOR='[data-selected]';
    var _trigger = $.fn.trigger;
    $.fn.trigger = function (event) {
        var $this = $(this), args = arguments, classname;
        if (event === 'tap' || event.type === 'tap') {
            var match = $this.closest(SELECTOR).get(0);
            if(match){
                match = $(match);
                classname = match.data('selected');
                match.addClass(classname);
                setTimeout(function () {
                    match.removeClass(classname);
                    _trigger.apply($this, args);
                    $this = match = null;
                }, DELAY);
                return this;
            }
        }
        _trigger.apply($this, args);
        return this;
    }
})(J.$);
;/*!/js/module/gj.Service.js*/
/**
 * 对zeptojs的ajax进行封装，实现离线访问
 * 推荐纯数据的ajax请求调用本方法，其他的依旧使用zeptojs自己的ajax
 * @Deprecated 用J.Cache代替
 */
J.Service = (function($){
    var UNPOST_KEY = 'gj_POST_DATA',
        GET_KEY_PREFIX = 'gj_GET_';
    var ajax = function(options){
        if(options.type == 'post'){
            _doPost(options);
        }else{
            _doGet(options);
        }
    }

    var _doPost = function(options){
        if(J.offline){//离线模式，将数据存到本地，连线时进行提交
            _setUnPostData(options.url,options.data);
            options.success('数据已存至本地');
        }else{//在线模式，直接提交
            $.ajax(options);
        }
    }
    var _doGet = function(options){
        var key = options.url +JSON.stringify(options.data);
        if(J.offline){//离线模式，直接从本地读取
            var result = _getCache(key);
            if(result){
                options.success(result.data,key,result.cacheTime);
            }else{//未缓存该数据
                options.success(result);
            }
        }else{//在线模式，将数据保存到本地
            var callback = options.success;
            options.success = function(result){
                _saveData2local(key,result);
                callback(result,key);
            }
            $.ajax(options);
        }
    }

    /**
     * 获取本地已缓存的数据
     * @private
     */
    var _getCache = function(key){
         return JSON.parse(window.localStorage.getItem(GET_KEY_PREFIX+key));
    }
    /**
     * 缓存数据到本地
     * @private
     */
    var _saveData2local = function(key,result){
        var data = {
            data : result,
            cacheTime : new Date()
        }
        window.localStorage.setItem(GET_KEY_PREFIX+key,JSON.stringify(data));
    }

    /**
     * 将post的数据保存至本地
     * @param url
     * @param result
     * @private
     */
    var _setUnPostData = function(url,result){
        var data = getUnPostData();
        data = data || {};
        data[url] = {
            data : result,
            createdTime : new Date()
        }
        window.localStorage.setItem(UNPOST_KEY,JSON.stringify(data));
    }
    /**
     *  获取尚未同步的post数据
     * @param url  没有就返回所有未同步的数据
     */
    var getUnPostData = function(url){
        var data = JSON.parse(window.localStorage.getItem(UNPOST_KEY));
        return (data && url ) ? data[url] : data;
    }
    /**
     * 移除未同步的数据
     * @param url 没有就移除所有未同步的数据
     */
    var removeUnPostData = function(url){
        if(url){
            var data = getUnPostData();
            delete data[url];
            window.localStorage.setItem(UNPOST_KEY,JSON.stringify(data));
        }else{
            window.localStorage.removeItem(UNPOST_KEY);
        }
    }

    /**
     * 同步本地缓存的post数据
     * @param url
     */
    var syncPostData = function(url,success,error){
        var unPostData = getUnPostData(url).data;
        $.ajax({
            url : url,
            contentType:'application/json',
            data : unPostData,
            type : 'post',
            success : function(){
                success(url);
            },
            error : function(){
                error(url);
            }
        })
    }
    /**
     * 同步所有的数据
     * @param callback
     */
    var syncAllPostData = function(success,error){
        var unPostData = getUnPostData();
        for(var url in unPostData){
            syncPostData(url,success,error);
        }
        removeUnPostData();
    }

    //copy from zepto
    function parseArguments(url, data, success, dataType) {
        var hasData = !$.isFunction(data)
        return {
            url:      url,
            data:     hasData  ? data : undefined,
            success:  !hasData ? data : $.isFunction(success) ? success : undefined,
            dataType: hasData  ? dataType || success : success
        }
    }

    var get = function(url, data, success, dataType){
        return ajax(parseArguments.apply(null, arguments))
    }

    var post = function(url, data, success, dataType){
        var options = parseArguments.apply(null, arguments)
        options.type = 'POST'
        return ajax(options)
    }

    var getJSON = function(url, data, success){
        var options = parseArguments.apply(null, arguments);
        options.dataType = 'json'
        return ajax(options)
    }
    var clear = function()
    {
        var storage = window.localStorage;
        var keys = [];
        for(var i = 0; i< storage.length; i++){
            var key = storage.key(i);
            key.indexOf(GET_KEY_PREFIX) == 0 && keys.push(key);
        }
        for(var i = 0; i < keys.length; i++){
            storage.removeItem(keys[i]);
        }
        storage.removeItem(UNPOST_KEY);
    }
    var removeItem = function(key)
    {
        var storage = window.localStorage;
        console.log(key)
        storage.removeItem(GET_KEY_PREFIX+key);
    }
    return {
        ajax : ajax,
        get : get,
        post : post,
        getJSON : getJSON,
        getUnPostData : getUnPostData,
        removeUnPostData : removeUnPostData,
        syncPostData : syncPostData,
        syncAllPostData : syncAllPostData,
        getCacheData : _getCache,
        saveCacheData : _saveData2local,
        remove:removeItem,
        clear : clear

    }
})(J.$);
;/*!/js/module/gj.Toast.js*/
/**
 *  消息组件
 */
J.Toast = (function($){
    var toast_type = 'toast',_toast,timer,
        //定义模板
        TEMPLATE = {
            toast : '<a href="#">{value}</a>',
            success : '<a href="#"><i class="icon checkmark-circle"></i>{value}</a>',
            error : '<a href="#"><i class="icon cancel-circle"></i>{value}</a></div>',
            info : '<a href="#"><i class="icon info-2"></i>{value}</a>'
        }

    var _init = function(){
        //全局只有一个实例
        $('body').append('<div id="gj_toast"></div>');
        _toast = $('#gj_toast');
        _subscribeCloseTag();
    }

    /**
     * 关闭消息提示
     */
    var hide = function(){
        J.anim(_toast,'scaleOut',function(){
            _toast.hide();
           _toast.empty();
        });
    }
    /**
     * 显示消息提示
     * @param type 类型  toast|success|error|info  空格 + class name 可以实现自定义样式
     * @param text 文字内容
     * @param duration 持续时间 为0则不自动关闭,默认为3000ms
     */
    var show = function(type,text,duration){
        if(timer) clearTimeout(timer);
        var classname = type.split(/\s/);
        toast_type = classname[0];
        _toast.attr('class',type).html(TEMPLATE[toast_type].replace('{value}',text)).show();
        J.anim(_toast,'scaleIn');
        if(duration !== 0){//为0 不自动关闭
            timer = setTimeout(hide,duration || J.settings.toastDuration);
        }
    }
    var _subscribeCloseTag = function(){
        _toast.on('tap','[data-target="close"]',function(){
            hide();
        })
    }
    _init();
    return {
        show : show,
        hide : hide
    }
})(J.$);
;/*!/js/module/gj.Tools.js*/
J.Tools = (function($){
	var createListHTML = function()
	{

	};
	/***
	*get openid
	**/
	var getOpenId =function(id)
	{
	    var openid = id;
	    localStorage.openid = openid;
	}
	/***
	*get cart data
	**/
	var getCart =function (sid)
	{
	    try{
			var c = localStorage.getItem(("cart"+sid));
	        if(c)
	        {
	            return JSON.parse(c);
	        }
	    }catch(e)
	    {}
	    var cart = {count:0,items:[],itemTotals:{}};
	    localStorage.setItem(("cart"+sid),JSON.stringify(cart));
	    return cart;
	}
	/**
	* id to search in cart
	**/
	var getItemByID = function(id,callback,sid)
	{
	    var cart = getCart(sid);
	    if(arguments[3])
	    {
			cart = arguments[3];
	    }
	    for(var i in cart.items)
	    {
	        var item = cart.items[i];
	        if(item.item.id==id)
	        {
	            callback(item,i,cart);
	            break;
	        }
	    }
	}
	/*****
	*refund status
	*
	****/
	var changeStatus = function(status,i)
	{
		switch(status)
		{
          case "1":
			return "审核中";
			break;
			case "2":
				$(".btn-close").eq(i).hide();
			return "审核不通过";
			break;
			case "3":
				$(".btn-close").eq(i).hide();
			return "申请取消,退款已关闭";
			break;
			case "4":
				$(".btn-close").eq(i).hide();
			return "已退款";
			break;
			case "5":
			return "申请退款正在审核中";
			break;
			case "6":
				$(".btn-close").eq(i).hide();
			return "退款中";
			break;
		}

	};
	var clearCartAndOrder = function()
	{
	    localStorage.removeItem("cart");
	    localStorage.removeItem("order");
	    order = {};
	};
	var showAddPanel = function(sid,e,copyData,me,ty)
    {
        e.preventDefault();
        e.stopPropagation();
        var cart = getCart(sid),
            itemId  = me.parent().attr("data-id");
		var idTxt;
        for(var i=0;i<copyData.length;i++)
        {

			if(copyData[i].item)
			{
				idTxt = copyData[i].item;
			}
			else
			{
				idTxt = copyData[i];
			}
            if(itemId==idTxt.id)
            {
				cart.items.push({"item":idTxt,"count":1});
				cart.count++;
				setTotalPrice(idTxt.present_price);
				$(".count").text(cart.count);
            //    toPayArr.push(itemId);
                break;
            }
        }

        localStorage.setItem("cart"+sid,JSON.stringify(cart));
        emptyStatus(cart.count);//价格为0状态
        me.addClass("displayhidden").next().removeClass("displayhidden");
        me.next().find(".counts").text(1);
        $(".count").show();
		     //新增
		var cartArr=[];
		if(J.Service.getCacheData("cartArr"))
		{
			cartArr = J.Service.getCacheData("cartArr").data||[];
		}
		$(".search-goods").each(function(){
			var _this = $(this);
			if(_this.find(".add-panel").hasClass("displayhidden"))
			{
				cartArr.push(_this.data("id"));
				cartArr = J.Util.nocopy(cartArr);
			}
		})
		J.Service.saveCacheData("cartArr",cartArr);
		if(ty==1)
		{
			indexLessPrice(".bp .price-value",".bg-cart",sid);
		}
    };

    var limitNumFunc = function(type,callback)
    {
    	switch(type)
    	{
    		case 1:
    		break;
    		case 2:
    			J.showToast("限购商品，您每天只能购买1件");
				if(typeof(callback)=="function") callback();
    		break;
    		case 3:
    		   	J.showToast("限购商品，您只能购买1件");
				if(typeof(callback)=="function") callback();
    		break;

    	}
    }
    var onItemClicked = function(e,me)
    {
        var gid = me.parent().attr("data-id");
        //移除专题类型详情
    //    J.Service.remove("ztlist");
        //提前请求详情接口数据
        api.getGoodsDetail(gid,function(data){
            if(data.status=="401")
            {
                J.showToast("商品已经下架");
                return false;
            }

			J.Service.saveCacheData("gid",gid);
            //成功获取数据
            J.Service.saveCacheData("details",data.data);
			var arr = [];
			$(".item-goods").each(function(){
				var me = $(this);
				if(me.find(".add-panel").hasClass("displayhidden"))
				{
					arr.push(me.data("id"));
				}
			})
			J.Service.saveCacheData("cartArr",arr);
			arr = null;
            window.location.href= url.details;
        });
    }
	/****
	*** 添加商品
	*** ty 点击页面类型 1首页
	** sid 商品id
	** obj指定
	*/
    var onItemCartIncrementClicked = function(sid,e,me,obj,ty)
    {
        e.preventDefault();
        e.stopPropagation();
        var cart = getCart(sid);
		if(me)
		{
			var idx = me.parent().parent().index(),
            buyType = me.parent().parent().data("type"), //1 无限制购买数量 2 商品每天限购一个 3商品限购一个
            itemId =me.data("id"),
			flag = false;
		}
		if(obj)
		{
			var idx = obj.idx,
				buyType = obj.buyType,
				itemId = obj.id,
				flag = false;
		}
        if(buyType && buyType==2|| buyType==3)
        {
        	limitNumFunc(buyType,function(){
				flag = true;
			});
        }
		if(flag) return false;
        getItemByID(itemId,function(item,i,cart){
            item.count++;
            cart.count++;
            me.prev().text(item.count);
            cart.items.splice(i,1,item);
            localStorage.setItem("cart"+sid,JSON.stringify(cart));
            $(".count").text(cart.count);
            setTotalPrice(item.item.present_price);
            //    J.showToast(config.ADD_SUCCESS);
        },sid);
        $(".count").show();
		if(ty==1)
		{
			indexLessPrice(".bp .price-value",".bg-cart",sid);
		}
    }
	/****
	**减少
	**/
    var onItemCartDecrementClicked = function(sid,e,me,obj,ty)
    {
        e.preventDefault();
        e.stopPropagation();
        var cart = getCart(sid);
        var idx = me.parent().parent().index(),
            itemId =me.data("id");
        getItemByID(itemId,function(item,i,cart){
            item.count--;
            cart.count--;
            emptyStatus(cart.count);//价格为0状态
            $(".count").text(cart.count);
			if(cart.count==0)
			{
				$(".count").hide();
			}
            if(!(item.count))
            {
                cart.items.splice(i,1);
                me.parent().addClass("displayhidden").prev().removeClass("displayhidden");
                localStorage.setItem("cart"+sid,JSON.stringify(cart));;
                setTotalPrice(-item.item.present_price);
                return false;
            }
            me.next().text(item.count);
            cart.items.splice(i,1,item);
            localStorage.setItem("cart"+sid,JSON.stringify(cart));
            setTotalPrice(-item.item.present_price);
        },sid);
		if(ty==1)
		{
			indexLessPrice(".bp .price-value",".bg-cart",sid);
		}
    }
	var emptyStatus = function (num)
    {
        var goToCart =$(".bg-cart");
        goToCart.removeClass("discolor");
        if(num<=0)
        {
            goToCart.addClass("discolor");
        }
    }
    /****
    *还差价格统计
    ***/
    var lessPrice = function(el,el2)
    {
      var startprice = 0;
        if(gj.Service.getCacheData("startprice"))
        {
          startprice = gj.Service.getCacheData("startprice").data
        }
        var subprice = +($(el).text());
        if(subprice<startprice)
        {
            $(el2).addClass("discolor").html('还差¥'+(startprice-subprice).toFixed(2));
        }
        else
        {
            $(el2).removeClass("discolor").text('去结算');
        }
    }
	var indexLessPrice = function(el,el2,sid)
    {
		var cart = getCart(sid),
			subprice = 0;;
        var startprice = +gj.Service.getCacheData("startprice").data||0;

		cart.items.forEach(function(dd){
			subprice+=dd.item.present_price*dd.count;
		});
        if(subprice<startprice)
        {
            $(el2).addClass("discolor");
           // $(el).parent().empty();
            $(el).parent().html('还差¥<i class="price-value">'+(startprice-subprice).toFixed(2)+'</i>');
        }
        else
        {

            $(el2).removeClass("discolor");
			$(el).parent().html('¥<i class="price-value">'+subprice.toFixed(2)+'</i>');
		}
    }
    function setTotalPrice(num)
    {
		var valueContainer
    	if($("footer .price"))
    	{
    		valueContainer = $("footer .price");
    	}
        if($("footer .price-value"))
        {
        	valueContainer = $("footer .price-value");
        }
        var current = parseFloat(valueContainer.text());
        valueContainer.text((current+(+num)).toFixed(2));
    };
	var footerBar = function(sid)
	{
        var cart = getCart(sid);
        var price = 0;
        cart.items.forEach(function(dd){
            price += dd.count*dd.item.present_price;
        });
        $("footer .price-value").text(price.toFixed(2));
		$("footer .count").text(cart.count).hide()
        if(cart.count>0)
        {
        	$("footer .count").show();
        }

	}
    var subCount = function(obj,arr)
	{
	    //订单小计
	    var total = 0;
	    var num = 0;//个数
	    arr = arr || [];
	    if(arr.length>0)
	    {
	        for(var i in obj.items) {
	            var item = obj.items[i];
	            for (var j in arr) {
	                if (arr[j] == item.item.id) {
	                    total += item.count*item.item.present_price;
	                    num +=item.count;
	                }
	            }
	        }
	        $(".price-value").text(total.toFixed(2));
	    }
	    else if($(".order-count"))
	    {
	        for(var i in obj.items)
	        {
	            var item = obj.items[i];
	            total += item.count*item.item.present_price;
	            num +=item.count;
	        }
	    }
	    payPrice = total.toFixed(2);
	    if($(".order-frist").next().data("price"))
	    {
	        payPrice = (total-($(".order-frist").next().data("price")||0)).toFixed(2);
	    }
	    else if($(".order-fullreduce").data("price"))
	    {
	        payPrice = (total-($(".order-fullreduce").data("price")||0)).toFixed(2);
	    }
	    $(".order-count").text(num);
	    if(payPrice<0)
	    {
	        payPrice = 0;
	    }
	    $(".order-price").text(payPrice);
	}
	var getShopAddress = function(lng,lat,sid,callback)
	{
		// api.getShopAdress(lng,lat,function(data){
  //           if(data.status!="200")
  //           {
  //               J.showToast(data.msg);
  //               return false;
  //           }
  //           J.Service.saveCacheData("adresParam",data.data);
  //         	if(typeof(callback)=="Function")
  //         	{
  //         		callback(sid,data.data);
  //         	}
  //         	return data.data;
            //自提点
           // App.page("ordercart").dtParam = dt;
        //});
	}
	/*****
	*merge array
	*****/
	var payPwdtoC = function(pwd)
	{
		var b = new Base64()

		var txt = b.encode((pwd.split("").reverse().join("")));

		return txt;
	}
	/***
	***reverse
	***/
	var reverse = function(strIn,pos,strOut){
	     if(pos<0)
	          return;
	      strOut+=strIn.charAt(pos--);
	              reverse(strIn,pos,strOut);

	};
	/**
	*
	*  Base64 encode / decode
	*/
	function Base64() {
	 
		var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	 
		//  method for encoding
		this.encode = function (input) {
			var output = "";
			var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
			var i = 0;
			input = utf8_encode(input);
			while (i < input.length) {
				chr1 = input.charCodeAt(i++);
				chr2 = input.charCodeAt(i++);
				chr3 = input.charCodeAt(i++);
				enc1 = chr1 >> 2;
				enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
				enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
				enc4 = chr3 & 63;
				if (isNaN(chr2)) {
					enc3 = enc4 = 64;
				} else if (isNaN(chr3)) {
					enc4 = 64;
				}
				output = output +
				keyStr.charAt(enc1) + keyStr.charAt(enc2) +
				keyStr.charAt(enc3) + keyStr.charAt(enc4);
			}
			return output;
		}
	 
		//  method for decoding
		this.decode = function (input) {
			var output = "";
			var chr1, chr2, chr3;
			var enc1, enc2, enc3, enc4;
			var i = 0;
			input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
			while (i < input.length) {
				enc1 = keyStr.indexOf(input.charAt(i++));
				enc2 = keyStr.indexOf(input.charAt(i++));
				enc3 = keyStr.indexOf(input.charAt(i++));
				enc4 = keyStr.indexOf(input.charAt(i++));
				chr1 = (enc1 << 2) | (enc2 >> 4);
				chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
				chr3 = ((enc3 & 3) << 6) | enc4;
				output = output + String.fromCharCode(chr1);
				if (enc3 != 64) {
					output = output + String.fromCharCode(chr2);
				}
				if (enc4 != 64) {
					output = output + String.fromCharCode(chr3);
				}
			}
			output = utf8_decode(output);
			return output;
		}
	 
		//  method for UTF-8 encoding
		utf8_encode = function (string) {
			string = string.replace(/\r\n/g,"\n");
			var utftext = "";
			for (var n = 0; n < string.length; n++) {
				var c = string.charCodeAt(n);
				if (c < 128) {
					utftext += String.fromCharCode(c);
				} else if((c > 127) && (c < 2048)) {
					utftext += String.fromCharCode((c >> 6) | 192);
					utftext += String.fromCharCode((c & 63) | 128);
				} else {
					utftext += String.fromCharCode((c >> 12) | 224);
					utftext += String.fromCharCode(((c >> 6) & 63) | 128);
					utftext += String.fromCharCode((c & 63) | 128);
				}
	 
			}
			return utftext;
		}
	 
		// //  method for UTF-8 decoding
		// utf8_decode = function (utftext) {
		// 	var string = "";
		// 	var i = 0;
		// 	var c = c1 = c2 = 0;
		// 	while ( i < utftext.length ) {
		// 		c = utftext.charCodeAt(i);
		// 		if (c < 128) {
		// 			string += String.fromCharCode(c);
		// 			i++;
		// 		} else if((c > 191) && (c < 224)) {
		// 			c2 = utftext.charCodeAt(i+1);
		// 			string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
		// 			i += 2;
		// 		} else {
		// 			c2 = utftext.charCodeAt(i+1);
		// 			c3 = utftext.charCodeAt(i+2);
		// 			string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
		// 			i += 3;
		// 		}
		// 	}
		// 	return string;
		// }
	}

  /***
   * 合并
   * @param arr
   * @returns {Array}
     */
	var unique = function(arr){
		var tmp = [];
		for(var i in arr)
		{
			if(tmp.indexOf(arr[i])==-1)
			{
				tmp.push(arr[i]);
			}
		}
		return tmp;
	};
  /***\
   *appPay 支付
   * app打开页面
   * @param [string] name title
   * @param [string] urlString url
   * @param [string] n 0 不刷新 null不传第一个参数 下拉刷新 2 搜索
   * @param n[string] 安卓  n=10 为显示积分攻略 n=11商品详情 n=12 搜索 n=13电话
   * "objc://URL-1-"+n+"-"+encodeURIComponent(titles+"-")+encodeURIComponent(urlString)
   * window.estate.redirect(n,urlString,name);
   */
   var appPay = function(obj)
    {
      if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
        var str = window.location.href+"?a=0";
        window.location = "objc://shucaipay-1-1-" + encodeURIComponent("支付-")+ encodeURIComponent(obj.pid+"-")+ encodeURIComponent(obj.payprice+"-")+ encodeURIComponent(obj.sid+"-")+encodeURIComponent("showorderlist");
      } else if (/(Android)/i.test(navigator.userAgent)) {
        if(localStorage.position)
        {
          var pos = JSON.parse(localStorage.position);
          window.location.href=url.order+"?mid="+pos.mid+"&isback=1";
        }
        window.estate.shucaipay(JSON.stringify(obj));
      }
    };
  /***
   * app分享
   * @param urlStr
   * @param name
   * @param content
   * @param imgUrlStr
   */
    var appToShare = function(urlStr,name,content,imgUrlStr)
    {
      if (/(Android)/i.test(navigator.userAgent))
      {
        window.estate.maicaiShare(name+'~'+content+'~'+imgUrlStr+"~"+urlStr);
      }
      else
      {
        var  str = "objc://maicaishare-1-1-"+encodeURIComponent(name+"-")+encodeURIComponent(urlStr+"-")+encodeURIComponent(imgUrlStr+"-")+encodeURIComponent(content);
        window.location = str;
      }
    };
  /***
   * 调用二维码
   */
    var scan = function()
    {
      if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
        var str = window.location.href+"?a=0";
        window.location = "objc://scan-1-1-" + encodeURIComponent("扫一扫-")+ encodeURIComponent(""+"-");
      } else if (/(Android)/i.test(navigator.userAgent)) {
        window.estate.scan();
      }
    }
  /***
   * 跳转至app首页
   */
  var appIndex = function () {
    if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
      var str = window.location.href+"?a=0";
      window.location = "objc://index-1-1-" + encodeURIComponent("至首页-")+ encodeURIComponent(""+"-");
    } else if (/(Android)/i.test(navigator.userAgent)) {
      window.estate.index();
    }
  }
  /***
   * 是否微信
   * @returns {boolean}
     */
    var isWX = function () {
      var ua = navigator.userAgent.toLowerCase();
      if(ua.match(/MicroMessenger/i)=="micromessenger") {
        return true;
      } else {
        return false;
      }
    };

   return {
		createHTML:createListHTML,
		status:changeStatus,
		clearCart:clearCartAndOrder,
		getItemByID:getItemByID,
		getCart:getCart,
		getId:getOpenId,
		emptyStatus:emptyStatus,
		setTotalPrice:setTotalPrice,
		showAddPanel:showAddPanel,
		incrementFunc:onItemCartIncrementClicked,
		decrementFunc:onItemCartDecrementClicked,
		toGoodsDetail:onItemClicked,
		subCount:subCount,
		getAdress:getShopAddress,
		lessPrice:lessPrice,
		footerBar:footerBar,
		limitBuy:limitNumFunc,
		indexLessPrice:indexLessPrice,
		unique:unique,
		translate:payPwdtoC,
        toAppPay:appPay,
        isWX:isWX,
        toScan:scan,
        toShare:appToShare,
     toIndex:appIndex
	}
})(J.$);

;/*!/js/module/gj.Transition.js*/
/**
 * page转场动画
 * 可自定义css动画
 */
J.Transition = (function($){
    var isBack,$current,$target,transitionName,
        animationClass = {
        //[[currentOut,targetIn],[currentOut,targetIn]]
        slide : [['slideLeftOut','slideLeftIn'],['slideRightOut','slideRightIn']],
        cover : [['','slideLeftIn'],['slideRightOut','']],
        slideUp : [['','slideUpIn'],['slideDownOut','']],
        slideDown : [['','slideDownIn'],['slideUpOut','']],
        popup : [['','scaleIn'],['scaleOut','']]
        };

    var _doTransition = function(){
        //触发 beforepagehide 事件
        $current.trigger('beforepagehide',[isBack]);
        //触发 beforepageshow 事件
        $target.trigger('beforepageshow',[isBack]);
        var c_class = transitionName[0]||'empty' ,t_class = transitionName[1]||'empty';
        $current.bind('webkitAnimationEnd.gj', _finishTransition).addClass('anim '+ c_class);
        $target.addClass('anim animating '+ t_class);
    }
    var _finishTransition = function() {
        $current.off('webkitAnimationEnd.gj');
        $target.off('webkitAnimationEnd.gj');
        //reset class
        $current.attr('class','');
        $target.attr('class','active');
        //add custom events
        !$target.data('init') && $target.trigger('pageinit').data('init',true);
        !$current.data('init') && $current.trigger('pageinit').data('init',true);
        //触发pagehide事件
        $current.trigger('pagehide',[isBack]);
        //触发pageshow事件
        $target.trigger('pageshow',[isBack]);

        $current.find('article.active').trigger('articlehide');
        $target.find('article.active').trigger('articleshow');
        $current = $target = null;//释放
    }

    /**
     * 执行转场动画，动画类型取决于目标page上动画配置(返回时取决于当前page)
     * @param current 当前page
     * @param target  目标page
     * @param back  是否为后退
     */
    var run = function(current,target,back){
        //关闭键盘
        $(':focus').trigger('blur');
        isBack = back;
        $current = $(current);
        $target = $(target);
        var type = isBack?$current.attr('data-transition'):$target.attr('data-transition');
        type = type|| J.settings.transitionType;
        //后退时取相反的动画效果组
        transitionName  = isBack ? animationClass[type][1] : animationClass[type][0];
        _doTransition();
    }

    /**
     * 添加自定义转场动画效果
     * @param name  动画名称
     * @param currentOut 正常情况下当前页面退去的动画class
     * @param targetIn   正常情况下目标页面进入的动画class
     * @param backCurrentOut 后退情况下当前页面退去的动画class
     * @param backCurrentIn 后退情况下目标页面进入的动画class
     */
    var addAnimation = function(name,currentOut,targetIn,backCurrentOut,backCurrentIn){
        if(animationClass[name]){
            console.error('该转场动画已经存在，请检查你自定义的动画名称(名称不能重复)');
            return;
        }
        animationClass[name] = [[currentOut,targetIn],[backCurrentOut,backCurrentIn]];
    }
    return {
        run : run,
        add : addAnimation
    }

})(J.$);
;/*!/js/module/gj.Util.js*/
/**
 * 常用工具类
 */
J.Util = (function($){
    var wxParam = function(name,name1,str)
    {
        var urlStr = window.location.href;
        if(urlStr.indexOf(name)>0&&urlStr.indexOf(name1)>0)
        {
            var idx1 = urlStr.indexOf(name1);
            var idx = urlStr.indexOf(name);
            return urlStr.substring(idx,idx1).replace(str,"");
        }
    }
    var getUrlParam = function(strName){
        var strHref = window.location.href;
        var intPos = strHref.indexOf("?");
        var strRight = strHref.substr(intPos + 1);

        var arrTmp = strRight.split("&");
        for(var i = 0; i < arrTmp.length; i++)
        {
            var arrTemp = arrTmp[i].split("=");
            if(arrTemp[0].toUpperCase() == strName.toUpperCase()) return arrTemp[1].replace(/#/, "");
        }

        return "";
    };
    var parseHash = function(hash){
        var tag,query,param={};
        var arr = hash.split('?');
        tag = arr[0];
        if(arr.length>1){
            var seg,s;
            query = arr[1];
            seg = query.split('&');
            for(var i=0;i<seg.length;i++){
                if(!seg[i])continue;
                s = seg[i].split('=');
                param[s[0]] = s[1];
            }
        }
        return {
            hash : hash,
            tag : tag,
            query : query,
            param : param
        }
    }

    /**
     * 格式化date
     * @param date
     * @param format
     */
    var formatDate = function(date,format){
        var o =
        {
            "M+" : date.getMonth()+1, //month
            "d+" : date.getDate(),    //day
            "h+" : date.getHours(),   //hour
            "m+" : date.getMinutes(), //minute
            "s+" : date.getSeconds(), //second
            "q+" : Math.floor((date.getMonth()+3)/3),  //quarter
            "S" : date.getMilliseconds(), //millisecond
			"Y": (new Date()).getFullYear()
        }
		
        if(/(y+)/.test(format))
            format=format.replace(RegExp.$1,(date.getFullYear()+"").substr(4 - RegExp.$1.length));
        for(var k in o)
            if(new RegExp("("+ k +")").test(format))
                format = format.replace(RegExp.$1,RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
        return format;
    }



    /***
     * 倒计时
     * @param time 分钟
     * @param el element
     * @param callback 回调方法
     * @typestr ":|-"显示类型
     getReduceTime(1分钟,".aas")
     */
    var getReduceTime = function(time,el,callback,typestr)
    {
        if(times)
        {
            clearInterval(times);
        }
        var endTime = (new Date().getTime())+time*60*1000;
        var times = setInterval(function(){
            var t = Math.floor((endTime- new Date().getTime())/1000);
            document.querySelector(el).innerHTML = t;
            if(typestr)
            {
                document.querySelector(el).innerHTML = formatDate((new Date(t*1000)),typestr)
            }
            if(t<=0)
            {
                clearInterval(times);
                if(typeof(callback)=="function")
                {
                    callback();
                }
                return false;
            }
        },1000);
    }
    /**
     * 判断是否测试地址
     * @return {[type]} [description]
     */
    var testFunc = function()
    {
        if(window.location.origin.indexOf("api")>0||window.location.origin.indexOf("test")>0)
        {
            return true;
        }
    };
    /**
     * 去重复数组内容
     * @param  {[type]} arr [description]
     * @return {[type]}     [description]
     */
    function unique(arr) {
        var result = [], hash = {};
        for (var i = 0, elem; (elem = arr[i]) != null; i++) {
            if (!hash[elem]) {
                result.push(elem);
                hash[elem] = true;
            }
        }
        return result;
    }

    return {
        parseHash : parseHash,
        formatDate : formatDate,
        getParam:getUrlParam,
        countDown:getReduceTime,
        getWxParam:wxParam,
        test:testFunc,
        nocopy:unique
    }

})(J.$);
/***
 * fastclick
 */
!function(){"use strict";function t(e,o){function i(t,e){return function(){return t.apply(e,arguments)}}var r;if(o=o||{},this.trackingClick=!1,this.trackingClickStart=0,this.targetElement=null,this.touchStartX=0,this.touchStartY=0,this.lastTouchIdentifier=0,this.touchBoundary=o.touchBoundary||10,this.layer=e,this.tapDelay=o.tapDelay||200,this.tapTimeout=o.tapTimeout||700,!t.notNeeded(e)){for(var a=["onMouse","onClick","onTouchStart","onTouchMove","onTouchEnd","onTouchCancel"],c=this,s=0,u=a.length;u>s;s++)c[a[s]]=i(c[a[s]],c);n&&(e.addEventListener("mouseover",this.onMouse,!0),e.addEventListener("mousedown",this.onMouse,!0),e.addEventListener("mouseup",this.onMouse,!0)),e.addEventListener("click",this.onClick,!0),e.addEventListener("touchstart",this.onTouchStart,!1),e.addEventListener("touchmove",this.onTouchMove,!1),e.addEventListener("touchend",this.onTouchEnd,!1),e.addEventListener("touchcancel",this.onTouchCancel,!1),Event.prototype.stopImmediatePropagation||(e.removeEventListener=function(t,n,o){var i=Node.prototype.removeEventListener;"click"===t?i.call(e,t,n.hijacked||n,o):i.call(e,t,n,o)},e.addEventListener=function(t,n,o){var i=Node.prototype.addEventListener;"click"===t?i.call(e,t,n.hijacked||(n.hijacked=function(t){t.propagationStopped||n(t)}),o):i.call(e,t,n,o)}),"function"==typeof e.onclick&&(r=e.onclick,e.addEventListener("click",function(t){r(t)},!1),e.onclick=null)}}var e=navigator.userAgent.indexOf("Windows Phone")>=0,n=navigator.userAgent.indexOf("Android")>0&&!e,o=/iP(ad|hone|od)/.test(navigator.userAgent)&&!e,i=o&&/OS 4_\d(_\d)?/.test(navigator.userAgent),r=o&&/OS [6-7]_\d/.test(navigator.userAgent),a=navigator.userAgent.indexOf("BB10")>0;t.prototype.needsClick=function(t){switch(t.nodeName.toLowerCase()){case"button":case"select":case"textarea":if(t.disabled)return!0;break;case"input":if(o&&"file"===t.type||t.disabled)return!0;break;case"label":case"iframe":case"video":return!0}return/\bneedsclick\b/.test(t.className)},t.prototype.needsFocus=function(t){switch(t.nodeName.toLowerCase()){case"textarea":return!0;case"select":return!n;case"input":switch(t.type){case"button":case"checkbox":case"file":case"image":case"radio":case"submit":return!1}return!t.disabled&&!t.readOnly;default:return/\bneedsfocus\b/.test(t.className)}},t.prototype.sendClick=function(t,e){var n,o;document.activeElement&&document.activeElement!==t&&document.activeElement.blur(),o=e.changedTouches[0],n=document.createEvent("MouseEvents"),n.initMouseEvent(this.determineEventType(t),!0,!0,window,1,o.screenX,o.screenY,o.clientX,o.clientY,!1,!1,!1,!1,0,null),n.forwardedTouchEvent=!0,t.dispatchEvent(n)},t.prototype.determineEventType=function(t){return n&&"select"===t.tagName.toLowerCase()?"mousedown":"click"},t.prototype.focus=function(t){var e;o&&t.setSelectionRange&&0!==t.type.indexOf("date")&&"time"!==t.type&&"month"!==t.type?(e=t.value.length,t.setSelectionRange(e,e)):t.focus()},t.prototype.updateScrollParent=function(t){var e,n;if(e=t.fastClickScrollParent,!e||!e.contains(t)){n=t;do{if(n.scrollHeight>n.offsetHeight){e=n,t.fastClickScrollParent=n;break}n=n.parentElement}while(n)}e&&(e.fastClickLastScrollTop=e.scrollTop)},t.prototype.getTargetElementFromEventTarget=function(t){return t.nodeType===Node.TEXT_NODE?t.parentNode:t},t.prototype.onTouchStart=function(t){var e,n,r;if(t.targetTouches.length>1)return!0;if(e=this.getTargetElementFromEventTarget(t.target),n=t.targetTouches[0],o){if(r=window.getSelection(),r.rangeCount&&!r.isCollapsed)return!0;if(!i){if(n.identifier&&n.identifier===this.lastTouchIdentifier)return t.preventDefault(),!1;this.lastTouchIdentifier=n.identifier,this.updateScrollParent(e)}}return this.trackingClick=!0,this.trackingClickStart=t.timeStamp,this.targetElement=e,this.touchStartX=n.pageX,this.touchStartY=n.pageY,t.timeStamp-this.lastClickTime<this.tapDelay&&t.preventDefault(),!0},t.prototype.touchHasMoved=function(t){var e=t.changedTouches[0],n=this.touchBoundary;return Math.abs(e.pageX-this.touchStartX)>n||Math.abs(e.pageY-this.touchStartY)>n?!0:!1},t.prototype.onTouchMove=function(t){return this.trackingClick?((this.targetElement!==this.getTargetElementFromEventTarget(t.target)||this.touchHasMoved(t))&&(this.trackingClick=!1,this.targetElement=null),!0):!0},t.prototype.findControl=function(t){return void 0!==t.control?t.control:t.htmlFor?document.getElementById(t.htmlFor):t.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")},t.prototype.onTouchEnd=function(t){var e,a,c,s,u,l=this.targetElement;if(!this.trackingClick)return!0;if(t.timeStamp-this.lastClickTime<this.tapDelay)return this.cancelNextClick=!0,!0;if(t.timeStamp-this.trackingClickStart>this.tapTimeout)return!0;if(this.cancelNextClick=!1,this.lastClickTime=t.timeStamp,a=this.trackingClickStart,this.trackingClick=!1,this.trackingClickStart=0,r&&(u=t.changedTouches[0],l=document.elementFromPoint(u.pageX-window.pageXOffset,u.pageY-window.pageYOffset)||l,l.fastClickScrollParent=this.targetElement.fastClickScrollParent),c=l.tagName.toLowerCase(),"label"===c){if(e=this.findControl(l)){if(this.focus(l),n)return!1;l=e}}else if(this.needsFocus(l))return t.timeStamp-a>100||o&&window.top!==window&&"input"===c?(this.targetElement=null,!1):(this.focus(l),this.sendClick(l,t),o&&"select"===c||(this.targetElement=null,t.preventDefault()),!1);return o&&!i&&(s=l.fastClickScrollParent,s&&s.fastClickLastScrollTop!==s.scrollTop)?!0:(this.needsClick(l)||(t.preventDefault(),this.sendClick(l,t)),!1)},t.prototype.onTouchCancel=function(){this.trackingClick=!1,this.targetElement=null},t.prototype.onMouse=function(t){return this.targetElement?t.forwardedTouchEvent?!0:t.cancelable&&(!this.needsClick(this.targetElement)||this.cancelNextClick)?(t.stopImmediatePropagation?t.stopImmediatePropagation():t.propagationStopped=!0,t.stopPropagation(),t.preventDefault(),!1):!0:!0},t.prototype.onClick=function(t){var e;return this.trackingClick?(this.targetElement=null,this.trackingClick=!1,!0):"submit"===t.target.type&&0===t.detail?!0:(e=this.onMouse(t),e||(this.targetElement=null),e)},t.prototype.destroy=function(){var t=this.layer;n&&(t.removeEventListener("mouseover",this.onMouse,!0),t.removeEventListener("mousedown",this.onMouse,!0),t.removeEventListener("mouseup",this.onMouse,!0)),t.removeEventListener("click",this.onClick,!0),t.removeEventListener("touchstart",this.onTouchStart,!1),t.removeEventListener("touchmove",this.onTouchMove,!1),t.removeEventListener("touchend",this.onTouchEnd,!1),t.removeEventListener("touchcancel",this.onTouchCancel,!1)},t.notNeeded=function(t){var e,o,i,r;if("undefined"==typeof window.ontouchstart)return!0;if(o=+(/Chrome\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1]){if(!n)return!0;if(e=document.querySelector("meta[name=viewport]")){if(-1!==e.content.indexOf("user-scalable=no"))return!0;if(o>31&&document.documentElement.scrollWidth<=window.outerWidth)return!0}}if(a&&(i=navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/),i[1]>=10&&i[2]>=3&&(e=document.querySelector("meta[name=viewport]")))){if(-1!==e.content.indexOf("user-scalable=no"))return!0;if(document.documentElement.scrollWidth<=window.outerWidth)return!0}return"none"===t.style.msTouchAction||"manipulation"===t.style.touchAction?!0:(r=+(/Firefox\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1],r>=27&&(e=document.querySelector("meta[name=viewport]"),e&&(-1!==e.content.indexOf("user-scalable=no")||document.documentElement.scrollWidth<=window.outerWidth))?!0:"none"===t.style.touchAction||"manipulation"===t.style.touchAction?!0:!1)},t.attach=function(e,n){return new t(e,n)},"function"==typeof define&&"object"==typeof define.amd&&define.amd?define(function(){return t}):"undefined"!=typeof module&&module.exports?(module.exports=t.attach,module.exports.FastClick=t):window.FastClick=t}();
$(function() {
  FastClick.attach(document.body);
});
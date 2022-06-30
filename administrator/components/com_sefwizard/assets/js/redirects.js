
/* SEF Wizard extension for Joomla 3.x
--------------------------------------------------------------
 Copyright (C) AddonDev. All rights reserved.
 Website: https://addondev.com
 GitHub: https://github.com/philip-sorokin
 Developer: Philip Sorokin
 Location: Russia, Moscow
 E-mail: philip.sorokin@gmail.com
 Created: January 2016
 License: GNU GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
--------------------------------------------------------------- */

(function(w,d){
	
	var emptyRule, blockRemoving, rulesNum = 0;
	
	d.addEventListener('DOMContentLoaded', function() {
		
		var rules = d.getElementById('redirect-rules'), 
			addButton = rules.parentNode.getElementsByClassName('redirect-add')[0],
			removeButtons = rules.getElementsByClassName('redirect-remove'),
			inputs = rules.getElementsByTagName('input');
			
		rulesNum = removeButtons.length;
		
		addCopyHandler(addButton);
		addRemoveHandler(removeButtons);
		addCheckboxHandler(inputs);
		
		var template = getFirstChild(rules);
		emptyRule = d.createElement('DIV');
		emptyRule.innerHTML = template.innerHTML;
		emptyRule = getFirstChild(emptyRule);
		template.parentNode.removeChild(template);
		
	});
	
	function getFirstChild(node, recursive) {
		var first = recursive ? node : node.firstChild;
		if (first) {
			return first.nodeType == 1 ? first : next(first, true);
		}
	}
	
	function addCheckboxHandler(checkboxes) {
		forEach(checkboxes, function(chb) {
			if (chb.getAttribute('type') === 'checkbox') {
				chb.onchange = function() {
					prev(chb.parentNode).value = chb.checked ? '1' : '';
				}
			}
		});
	}
	
	function addCopyHandler(btn) {
		btn.onclick = function() {
			rulesNum++;
			var rules = d.getElementById('redirect-rules'), 
				newRule = emptyRule.cloneNode(true);
			rules.appendChild(newRule);
			newRule.getElementsByClassName('redirect-rule-cnt')[0].firstChild.nodeValue = rulesNum;
			addRemoveHandler(newRule.getElementsByClassName('redirect-remove'));
			addCheckboxHandler(newRule.getElementsByTagName('input'));
			var scrollOffset = d.body.scrollHeight || d.documentElement.scrollHeight,
				windowHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
			if (scrollOffset > windowHeight + 75)
			{
				w.scroll(0, scrollOffset);
			}
		};
	}
	
	function addRemoveHandler(buttons) {
		forEach(buttons, function(btn) {
			btn.onclick = function() {
				if (!blockRemoving) {
					blockRemoving = true;
					var rule = this.parentNode,
						rules = rule.parentNode;
					if (rulesNum > 1) {
						rules.removeChild(rule);
						rulesNum--;
						forEach(rules.getElementsByClassName('redirect-rule-cnt'), function(id, i) {
							id.firstChild.nodeValue = i + 1;
						});
					} else {
						clean(rule, true);
					}
					setTimeout(function() {
						blockRemoving = false;
					}, 100);
				}
			};
		});
	}
	
	function clean(rule, placeholder) {
		forEach(rule.getElementsByTagName('input'), function(input) {
			var type = input.getAttribute('type');
			if (type === 'text' && placeholder) {
				input.value = input.name === 'source[]' ? 'http://example.com/source' : 'destination';
			} else if (input.name !== 'code[]') {
				input.value = '';
			} else if (type === 'checkbox') {
				input.checked = '';
			}
		});
		return rule;
	}
	
	function prev(node) {
		if (node) {
			var prevNode = node.previousSibling;
			if (prevNode) {
				return prevNode.nodeType == 1 ? prevNode : prev(prevNode);
			}
		}
	}
	
	function next(node) {
		if (node) {
			var nextNode = node.nextSibling;
			if (nextNode) {
				return nextNode.nodeType == 1 ? nextNode : prev(nextNode);
			}
		}
	}
	
	function forEach(collection, callback) {
		for (var i = 0, len = collection.length; i < len; i++) {
			if (collection[i] && callback(collection[i], i) === false) {
				break;
			}
		}
		return collection;
	};
	
})(window, document);

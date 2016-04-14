var formatter = {
	uptime: Fmt.uptime,

	date: Fmt.date,
	fwdate: Fmt.fwdate,

	sizenspeed: function (i, label, part) {
			label += part;
			var prev, now = new Date;

			now = now.getTime()/1000;

			if (label in controller.counts)
				prev = controller.counts[label];
			else
				prev = { timestamp: now - 1, value: i };

			var save = (controller.counts[label] = {});
			save.timestamp = now;
			save.value = i;

			if (prev.value*1 > i*1)
				prev.value = i;

			var speed = Math.round((i - prev.value)/(now - prev.timestamp)),
			    speed_str = [
					(speed < 1024) ? speed.toString()+' ' : Size.bin_scaled(speed),
					_l("bytes/sec") ].join('');

			if (i < 1024)
				return [i.toString(), " ", _l("byte"), " (", speed_str, ")"].join("");

			return [
				Size.bin_scaled(i), _l("byte"),
				' (', speed_str, ")"/*,
				"<br/> <small>(", Size.beauty(i), " ", _l("byte"), ")</small>"*/].join("");
		},

	memsize: function (i) {
			if (i < 1024)
				return [i.toString(), " ", _l("byte")].join("");
			return [Size.bin_scaled(i), _l("byte")].join('');
		},

	usage: function (i) {
			var percent = Math.round(i/10);
			return ["<div class='gauge_value'>",
				percent.toString(),
				"%</div><div class='gauge_mini' style='margin: .1em 0px 0; height: .9em; width: 8em;'>",
				"<div class='mercury_mini' style='width: ",
				Math.round(8.0*i/10)/100,
				"em; height: .9em'></div></div>"].join('');
		},

	size: function (i) {
			if (i < 1024)
				return [i.toString(), " ", _l("byte")].join("");
			return [
				Size.bin_scaled(i), _l("byte"),
				"<br/> <small>(", Size.beauty(i), " ", _l("byte"), ")</small>"].join("");
		},

	shortsize: function (i) {
			if (i < 1024)
				return [i.toString(), " ", _l("byte")].join("");
			return [
				Size.bin_scaled(i), _l("byte")].join("");
		},

	filesys: function (value) {
			switch (value) {
				case "FUSEBLK": return "NTFS";
			}
			return value;
		},

	accessMode: function (value) {
			switch (value) {
				case 'rw': return _l('Full');
				case 'ro': return _l('Read only');
			}
			return '-';
		},

	percent: function (value) {
			return value*1 + '%';
		},

	numToIp: function (value) {
			return IP.toStr(value);
		},

	bitrate: function (value) {
			return Size.dec_scaled(value) + _l("bit/sec");
		},

	bi_bitrate: function (value) {
			var ud = value.split('/');
			return [formatter.bitrate(ud[0]), ' <small>/</small> ', formatter.bitrate(ud[1])].join('');
		},

	kbitrate: function (value) {
			return formatter.bitrate(value * 1000);
		},

	bi_kbitrate: function (value) {
			var ud = value.split('/');
			return [Math.round(ud[0]/100)/10, ' / ', Math.round(ud[1]/100)/10, ' ', lang.kilo.charAt(2), _l("bit/sec")].join('');
		},

	dbm_level: function (value) {
			return [value*1, _l("dBm")].join(' ');
		},

	bi_dbm_levels: function (value) {
			var ud = value.split('/');
			return [ud[0]*1, ' <small>/</small> ', ud[1]*1, _l("dBm")].join('');
		},

	cbm_level: function (value) {
			return [value/10, _l("dBm")].join(' ');
		},

	bi_cbm_levels: function (value) {
			var ud = value.split('/');
			return [ud[0] != 0x7FFFFE ? ud[0]/10 : '-', '/', ud[1]/10, _l("dBm")].join(' ');
		},

	wlanState: function (value) {
			return _l("Not connected");
		},

	absent: function (value) {
			return _f("Absent");
		},

	diskNode: function (value) {
			return value.entitify();//.toString().replace(['cdrom'], ['CD-ROM']);
		},

	present: function (value) {
			return _m(value ? "Connected" : "Not found");
		},

	presenti: function (value) {
		return _l(value ? "Connected" : "Not found");
	},

	_pins: { "NONE": "None", "BAD": "Bad", "ERROR": "Error", "OK": "Ok", "NOT_SUPPORT": "Not supported" },

	modem_pin: function (value) {
			if (value in formatter._pins)
				return _l(formatter._pins[value]);
			return "-";
		},

	onoff: function (value) {
			return _m(value ? "On" : "Off");
		},

	ONOFF: function (value) {
			return _m(value != "OFF" ? "On" : "Off");
		},

	lang: function (value) {
			return _l(value).entitify();
		},

	cell_id: function (value) {
			return value.entitify();
		},

	uicc_id: function (value) {
			value = value.entitify();
			switch (value.length) {
			case 10: return value;
			case 19: return value.substr(8, 10);
			case 20: return value.substr(9, 10);
			}
			return "0";
		},

	none: function (str) {
			return str.entitify();
		}
};

function statusLeaf(title_id, data, nodata) {
	this.tbl = document.createElement("table");
	this.tbl.className = 'status';
	this.height = 0;
	if (title_id) {
		var thead = this.tbl.createTHead(),
		    cell = document.createElement("th");
		thead.insertRow(-1).appendChild(cell);
		cell.colSpan = 2;
		cell.innerHTML = controller.getHeadLink(controller.getLabel(title_id), title_id);
		cell.className = "check";
	}

	if (data) {
		this.addRows(data, title_id);
		this.finish(nodata);
	}
}

statusLeaf.prototype = {
	newRow: function () {
			var row = this.tbl.insertRow(-1);
			row.className = (!this.height ? "first ":"")+((this.height++ & 1) ? "odd" : "even");
			return row;
		},

	addRow: function (label, value) {
			var row = this.newRow(),
			    l = row.insertCell(0);

			switch (label) {
			case 'node:':
				l.className = 'node_name';
				l.innerHTML = value;
				l.colSpan = 2;
				break;

			case 'subnode:':
				l.className = 'name';
				l.innerHTML = value;
				l.colSpan = 2;
				break;

			case 'resume:':
				l.className = 'empty';
				l.innerHTML = value;
				l.colSpan = 2;
				break;

			default:
				l.className = 'name';
				l.innerHTML = label;
				l = row.insertCell(1);
				l.className = 'value';
				l.innerHTML = value;
			}
		},

	addBreak: function (label) {
			var l = this.newRow().insertCell(0);
			l.className = 'empty';
			l.colSpan = 2;
			l.innerHTML = label;
		},

	finish: function (nodata) {
			if (!this.height)
				this.addBreak(nodata || _m('Not connected'));
		},

	addField: function (leaf_id, id, rec) {
			var ln, ln_id, name, value, 
			    links = controller.rows_links;

			if (typeof rec == 'object') {
				sub_id = [id, leaf_id].join('/');
				for (ln in rec) {
					value = rec[ln];
					if (ln in links) {
						var href = links[ln];
						if (typeof href == 'object') {
							if (sub_id in href)
								value = controller.link(value, href[sub_id]);
						} else
							value = controller.link(value, href);
					} else
						if (sub_id in links)
							value = controller.link(value, links[sub_id]);

					this.addRow([controller.getLabel(ln/*+'/'+sub_id*/), ':'].join(''), value);
				}
			} else {
				value = rec;
				if (id in links) {
					var href = links[id];
					if (typeof href == 'object') {
						if (leaf_id in href)
							value = controller.link(rec, href[leaf_id]);
					} else
						value = controller.link(rec, href);
				} else
					if (leaf_id in links)
						value = controller.link(rec, links[leaf_id]);

				this.addRow([controller.getLabel(id/*+'/'+leaf_id*/), ':'].join(''), value);
			}
		},

	addRows: function (data, title_id) {
		if (!data)
			return;

		var id, ln, rec, leaf_id, ln_id, name, value, sub_id,
		    links = controller.rows_links;

		for (id in data) {
			rec = data[id],
			leaf_id = title_id || '';
			if (typeof rec == 'object') {
				sub_id = [id, leaf_id].join('/');
				for (ln in rec) {
					value = rec[ln];
					if (ln in links) {
						var href = links[ln];
						if (typeof href == 'object') {
							if (sub_id in href)
								value = controller.link(value, href[sub_id]);
						} else
							value = controller.link(value, href);
					} else
						if (sub_id in links)
							value = controller.link(value, links[sub_id]);

					this.addRow([controller.getLabel(ln/*+'/'+sub_id*/), ':'].join(''), value);
				}
			} else {
				value = rec;
				if (id in links) {
					var href = links[id];
					if (typeof href == 'object') {
						if (leaf_id in href)
							value = controller.link(rec, href[leaf_id]);
					} else
						value = controller.link(rec, href);
				} else
					if (leaf_id in links)
						value = controller.link(rec, links[leaf_id]);

				this.addRow([controller.getLabel(id/*+'/'+leaf_id*/), ':'].join(''), value);
			}
		}
	}
};

var controller = {
	counts: {},
	head_links: {},
	rows_links: {},
	labels: {},
	formatters: {},
	query: '/req/status',
	ajax: null,
	timer: 0,
	lock: 0,

	subOnReq: function (aj, state) {
		if (aj.readyState == 4) {
			if (aj.status == 200)
				this.renderAll(aj.responseText);
			this.ajax = null;
			this.lock = 0;
		}
	},

	req: function () {
			if (this.ajax !== null)
				this.ajax.abort();

			this.ajax = ajaxGet(this.query, this.subOnReq, this);
		},

	restoreRefresh: function() {
			var el = $('interval').el,
			    interval = el.value,
			    cookie_interval = document.cookie.match('(?:^|;)\\s*interval=([^;]*)');

			if (cookie_interval) {
				var interval = decodeURIComponent(cookie_interval[1]);
				el.value = interval;
			}
			return interval;
		},

	autoRefresh: function (interval) {
			if (this.timer)
				clearInterval(this.timer);
			document.cookie = 'interval='+encodeURIComponent(interval);
			this.timer = interval != 0 ? setInterval("if (!controller.lock) { ++controller.lock; controller.req(); }", interval*100) : 0;
		},

	renderAll: function (text) {
		},

	formatData: function (data, path, item) {
			var it;
			path += item;
			for (it in data)
				if (it in this.formatters)
					data[it] = this.formatters[it](data[it], path, it);
				else
					if (typeof data[it] != 'string')
						this.formatData(data[it], path, it);
					else
						data[it] = formatter.none(data[it]);
		},

	getHeadLink: function (text, id) {
			if (id in this.head_links)
				text = ["<a href='", this.head_links[id], "'>", text, "</a>"].join('');
			return text;
		},

	getLabel: function (id) {
			return (id in this.labels) ? this.labels[id] : id;
		},

	appendLeaf: function (place, id, data, nodata) {
			var leaf = new statusLeaf(id, data, nodata);
			leaf.finish();
			place.appendChild(leaf.tbl);
		},

	link: function (text, href) {
			if (!href || text.charAt(0) == '<')
				return text;
			return ["<a href='", href, "'>", text, "</a>"].join('');
		}
};

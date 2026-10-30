"""Seven-hills topographic contour map for the 7Hills Digital hero.

Iso-lines of a height field made of seven gaussian "hills", traced with
marching squares and joined into smooth polylines. Output is a single-colour
SVG used as a CSS mask, so the stroke colour always comes from brand tokens.
"""
import math
import sys

W, H = 1600, 900
NX, NY = 224, 126
HILLS = [  # (x, y, height, spread) in viewBox units
    (1190, 270, 1.00, 125),
    (1430, 520, 0.85, 115),
    (1010, 600, 0.75, 120),
    (1280, 780, 0.62, 105),
    (800, 250, 0.58, 115),
    (1530, 130, 0.60, 95),
    (610, 700, 0.48, 125),
]


def height(x, y):
    return sum(h * math.exp(-((x - hx) ** 2 + (y - hy) ** 2) / (2 * s * s)) for hx, hy, h, s in HILLS)


xs = [i * W / (NX - 1) for i in range(NX)]
ys = [j * H / (NY - 1) for j in range(NY)]
F = [[height(x, y) for x in xs] for y in ys]


def interp(p1, p2, v1, v2, lvl):
    t = (lvl - v1) / (v2 - v1) if v2 != v1 else 0.5
    return (p1[0] + t * (p2[0] - p1[0]), p1[1] + t * (p2[1] - p1[1]))


def segments(lvl):
    segs = []
    for j in range(NY - 1):
        for i in range(NX - 1):
            c = [(xs[i], ys[j]), (xs[i + 1], ys[j]), (xs[i + 1], ys[j + 1]), (xs[i], ys[j + 1])]
            v = [F[j][i], F[j][i + 1], F[j + 1][i + 1], F[j + 1][i]]
            idx = sum(1 << k for k in range(4) if v[k] > lvl)
            if idx in (0, 15):
                continue
            e = {}
            for k, (a, b) in enumerate([(0, 1), (1, 2), (2, 3), (3, 0)]):
                if (v[a] > lvl) != (v[b] > lvl):
                    e[k] = interp(c[a], c[b], v[a], v[b], lvl)
            pts = list(e.values())
            if len(pts) == 2:
                segs.append((pts[0], pts[1]))
            elif len(pts) == 4:
                segs.append((pts[0], pts[1]))
                segs.append((pts[2], pts[3]))
    return segs


def key(p):
    return (round(p[0], 2), round(p[1], 2))


def chain(segs):
    adj = {}
    for a, b in segs:
        adj.setdefault(key(a), []).append(b)
        adj.setdefault(key(b), []).append(a)
    used = set()
    lines = []
    for a, b in segs:
        if (key(a), key(b)) in used:
            continue
        line = [a, b]
        used.add((key(a), key(b)))
        used.add((key(b), key(a)))
        for direction in (0, 1):
            while True:
                end = line[-1] if direction == 0 else line[0]
                nxt = None
                for n in adj.get(key(end), []):
                    if (key(end), key(n)) not in used:
                        nxt = n
                        break
                if nxt is None:
                    break
                used.add((key(end), key(nxt)))
                used.add((key(nxt), key(end)))
                if direction == 0:
                    line.append(nxt)
                else:
                    line.insert(0, nxt)
        if len(line) > 4:
            lines.append(line)
    return lines


def path(line):
    pts = line[::2] + ([line[-1]] if len(line) % 2 == 0 else [])
    d = f"M{pts[0][0]:.0f} {pts[0][1]:.0f}"
    for p in pts[1:]:
        d += f"L{p[0]:.0f} {p[1]:.0f}"
    return d


levels = [0.05 + 0.07 * k for k in range(14)]
out = [f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {W} {H}" fill="none" stroke="#000" stroke-width="1.1" stroke-linejoin="round" stroke-linecap="round">']
for n, lvl in enumerate(levels):
    width = "1.6" if n % 4 == 3 else "1.1"
    ds = "".join(path(l) for l in chain(segments(lvl)))
    if ds:
        out.append(f'<path stroke-width="{width}" d="{ds}"/>')
out.append("</svg>")
sys.stdout.write("".join(out))

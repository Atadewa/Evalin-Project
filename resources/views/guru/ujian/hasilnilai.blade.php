<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <title>Hasil Ujian</title>
    <style>
      body {
        font-family: 'Times New Roman', Times, serif;
      }
      .kop {
        text-align: center;
        margin-bottom: 20px;
      }
      .kop img {
        float: left;
        width: 70px;
        height: 70px;
        margin-bottom: 5px;
      }
      .kop h2 {
        margin: 0;
      }
      .kop p {
        margin: 2px;
      }
      table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
      }
      th,
      td {
        border: 1px solid #000;
        padding: 6px;
        text-align: center;
      }
      .info {
        margin-top: 20px;
        margin-bottom: 10px;
      }
      .info td {
        border: none;
        text-align: left;
      }
      .judul {
        text-align: center;
        font-weight: bold;
        text-decoration: underline;
        margin-top: 20px;
      }

      @media print {
        .kop {
          position: running(header);
        }

        @page {
          @top-center {
            content: element(header);
          }
        }
      }
    </style>
  </head>
  <body>
    <htmlpageheader name="kop">
      <div class="kop">
        <img src="{{ public_path("images/logo.png") }}" alt="logo" />
        <h2>Evalin</h2>
        <p>
          Website:
          <a href="http://evalin.sch.id">evalin.sch.id</a>
          | Email:
          <a href="mailto:admin@evalin.sch.id">admin@evalin.sch.id</a>
        </p>
        <hr />
      </div>
    </htmlpageheader>

    <sethtmlpageheader name="kop" value="on" show-this-page="1" />

    <div class="judul">
      HASIL UJIAN {{ strtoupper($ujian->name ?? "XXXX") }}
    </div>

    <table class="info">
      <tr>
        <td width="150"><b>Tanggal</b></td>
        <td>
          :
          {{ \Carbon\Carbon::parse($ujian->tanggal)->translatedFormat("d F Y") }}
        </td>
      </tr>
      <tr>
        <td><b>Mata Pelajaran</b></td>
        <td>: {{ $ujian->mataPelajaran->nama_mapel ?? "-" }}</td>
      </tr>
      <tr>
        <td><b>Kelas</b></td>
        <td>: {{ $ujian->kelas->pluck("nama_kelas")->implode(", ") }}</td>
      </tr>
      <tr>
        <td><b>Pengajar</b></td>
        <td>: {{ $ujian->guru->user->name ?? "-" }}</td>
      </tr>
    </table>

    <table>
      <thead>
        <tr>
          <th>No.</th>
          <th>Nama Siswa</th>
          <th>NIS</th>
          @if (in_array("total_nilai", $kolomNilai))
            <th>Nilai LLAMA3</th>
          @endif

        </tr>
      </thead>
      <tbody>
        @foreach ($hasilujian->groupBy(fn ($item) => $item->siswa?->kelas?->nama_kelas) as $namaKelas => $siswaGroup)
          @foreach ($siswaGroup as $key => $siswas)
            <tr>
              <td>{{ $key + 1 }}</td>
              <td style="text-align: left">
                {{ $siswas->siswa?->user?->name }}
              </td>
              <td>{{ $siswas->siswa?->nis }}</td>
              @if (in_array("total_nilai", $kolomNilai))
                <td>{{ $siswas->total_nilai ?? 0 }}</td>
              @endif
            </tr>
          @endforeach
        @endforeach
      </tbody>
    </table>
  </body>
</html>

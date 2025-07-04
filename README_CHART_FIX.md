# วิธีการแก้ไขปัญหากราฟไม่แสดงใน Labor Demand Report

## สาเหตุของปัญหา
1. โค้ดสำหรับ Chart.js อยู่ในไฟล์ Blade template โดยตรง ซึ่งไม่เหมาะสมกับการทำงานร่วมกับ Vite และ Laravel Asset Management
2. Chart.js ไม่ถูกโหลดอย่างถูกต้อง และอาจมีความขัดแย้งกันระหว่างเวอร์ชัน
3. การอัพเดทกราฟและการเรียก DOM elements อาจจะไม่สอดคล้องกับ lifecycle ของ Livewire

## การแก้ไขปัญหา
1. สร้างไฟล์ JavaScript แยกต่างหากสำหรับการจัดการกราฟ: `resources/js/pages/labor-demand-charts.js`
2. เพิ่มไฟล์นี้ใน `vite.config.js` เพื่อให้ถูก build รวมกับ assets อื่นๆ
3. ปรับปรุงโค้ด HTML ใน blade template ให้ใช้ script จากไฟล์ที่สร้างขึ้น
4. ตรวจสอบและปรับปรุงการส่งข้อมูลระหว่าง Livewire component และ JavaScript

## วิธีการ Build และ Run Project
1. อัพเดท dependencies (ถ้าจำเป็น):
   ```
   composer update
   npm update
   ```

2. Build assets ใหม่:
   ```
   npm run build
   ```
   
3. หรือหากต้องการ build ในโหมด development:
   ```
   npm run dev
   ```

4. หากมีการแก้ไขไฟล์ JS หรือ CSS เพิ่มเติม อย่าลืม build assets ใหม่

## วิธีการปรับแต่งกราฟเพิ่มเติม
1. แก้ไขไฟล์ `resources/js/pages/labor-demand-charts.js` เพื่อปรับแต่งลักษณะหรือฟังก์ชันการทำงานของกราฟ
2. ชนิดของกราฟ options และ plugins ต่างๆ สามารถปรับแต่งได้ตามต้องการโดยอ้างอิงจาก [Chart.js Documentation](https://www.chartjs.org/docs/latest/)

## การดีบัก
1. เปิด Browser Developer Console เพื่อดูข้อความ log และ errors
2. ตรวจสอบว่าไฟล์ JavaScript ถูกโหลดอย่างถูกต้อง
3. มีการแสดง log เมื่อ:
   - โหลดหน้าเว็บ
   - คลิกเปลี่ยนมุมมอง (ตาราง, กราฟแท่ง, กราฟวงกลม)
   - อัพเดทข้อมูล

## หมายเหตุ
- การพัฒนาหน้ารายงานอื่นๆ ควรแยก JavaScript เป็นไฟล์ต่างหากเช่นกัน
- หากมีปัญหากับ Chart.js เวอร์ชันปัจจุบัน สามารถระบุเวอร์ชันที่ต้องการได้ในคำสั่ง `npm install chart.js@x.x.x`
